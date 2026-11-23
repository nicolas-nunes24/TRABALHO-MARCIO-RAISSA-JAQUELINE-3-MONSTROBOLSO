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
    $loja = new LojaModel();

    switch ($acao) {
        case 'listar':
            $mochila = $loja->mochila($perfilId);
            $itens = array_map(
                fn(Item $i) => $i->paraArray($mochila[$i->getId()] ?? 0),
                $loja->listarItens()
            );
            responder(['ok' => true, 'moeda' => $loja->saldo($perfilId), 'itens' => $itens]);

        case 'comprar':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                responder(['ok' => false, 'mensagem' => 'Método inválido.'], 405);
            }
            $r = $loja->comprar($perfilId, (int)($corpo['item_id'] ?? 0), (int)($corpo['quantidade'] ?? 1));
            responder(['ok' => true, 'mensagem' => $r['item'] . ' adicionado à mochila!', 'moeda' => $r['moeda'], 'qtd' => $r['qtd']]);

        default:
            responder(['ok' => false, 'mensagem' => 'Ação desconhecida.'], 400);
    }
} catch (SaldoInsuficienteException | ItemNaoEncontradoException | InvalidArgumentException $e) {
    responder(['ok' => false, 'mensagem' => $e->getMessage()], 400);
} catch (PDOException $e) {
    responder(['ok' => false, 'mensagem' => 'Erro no banco de dados.'], 500);
}