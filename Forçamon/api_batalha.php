<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/classes/autoload.php';

function responder(array $dados, int $status = 200): void
{
    http_response_code($status);
    echo json_encode($dados, JSON_UNESCAPED_UNICODE);
    exit;
}

$perfilId = (int)($_SESSION['id_perfil'] ?? 1);
$corpo = json_decode(file_get_contents('php://input'), true) ?? [];
$acao  = $_GET['acao'] ?? $corpo['acao'] ?? '';

try {
    switch ($acao) {
        case 'mochila':
            $mochila = new Mochila($perfilId);
            responder(['ok' => true, 'itens' => $mochila->listar()]);

        case 'usar_item':
            $mochila = new Mochila($perfilId);
            $item = $mochila->consumir((int)($corpo['item_id'] ?? 0));
            responder(['ok' => true, 'item' => $item]);

        case 'finalizar':
            $service = new BatalhaService();
            $res = $service->registrarResultado(
                $perfilId,
                $corpo['time_jogador'] ?? [],
                $corpo['time_oponente'] ?? [],
                $corpo['resultado'] ?? 'derrota',
                (int)($corpo['turnos'] ?? 1)
            );
            responder(['ok' => true] + $res);

        default:
            responder(['ok' => false, 'mensagem' => 'Ação inválida.'], 400);
    }
} catch (ItemNaoEncontradoException $e) {
    responder(['ok' => false, 'mensagem' => $e->getMessage()], 400);
} catch (Throwable $e) {
    responder(['ok' => false, 'mensagem' => 'Erro na operação.'], 500);
}