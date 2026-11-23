<?php
require_once 'registrar_log.php';
header('Content-Type: application/json; charset=utf-8');

$dados = json_decode(file_get_contents('php://input'), true);

if (!empty($dados['categoria']) && !empty($dados['mensagem'])) {
    registrarLog($dados['categoria'], $dados['mensagem']);
    echo json_encode(['ok' => true]);
    exit;
}

http_response_code(400);
echo json_encode(['ok' => false]);