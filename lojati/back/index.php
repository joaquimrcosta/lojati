<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once 'config.php';

$rota = $_GET['rota'] ?? '';
$metodo = $_SERVER['REQUEST_METHOD'];

if ($rota === 'clientes') {

    // GET: Listar clientes
    // No arquivo index.php (na rota GET):
if ($metodo === 'GET') {
    try {
        // Force o PDO a retornar colunas em minúsculas
        $pdo->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);

        $stmt = $pdo->query("SELECT * FROM clientes");
        $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($clientes);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["erro" => "Erro ao buscar clientes: " . $e->getMessage()]);
    }
    exit;
}

    // POST: Cadastrar cliente
    if ($metodo === 'POST') {
        $input = json_decode(file_get_contents("php://input"), true);

        $nome = $input['nome'] ?? $_POST['nome'] ?? '';
        $email = $input['email'] ?? $_POST['email'] ?? '';
        $telefone = $input['telefone'] ?? $_POST['telefone'] ?? '';

        if (empty($nome) || empty($email)) {
            http_response_code(400);
            echo json_encode(["erro" => "Nome e E-mail são obrigatórios!"]);
            exit;
        }

        try {
            $sql = "INSERT INTO clientes (nome, email, telefone) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nome, $email, $telefone]);

            http_response_code(201);
            echo json_encode(["mensagem" => "Cliente cadastrado com sucesso!"]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(["erro" => "Erro ao salvar no banco: " . $e->getMessage()]);
        }
        exit;
    }
}

http_response_code(404);
echo json_encode(["erro" => "Rota não encontrada. Use ?rota=clientes"]);
?>