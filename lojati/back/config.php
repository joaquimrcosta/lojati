<?php
$host = "localhost";
$banco = "lojati";
$usuario = "root";
$senha = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$banco;charset=utf8", $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["erro" => "Erro na conexão com o banco: " . $e->getMessage()]);
    exit;
}
?>