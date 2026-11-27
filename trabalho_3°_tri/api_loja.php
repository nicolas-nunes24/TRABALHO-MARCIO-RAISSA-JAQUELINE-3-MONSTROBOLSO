<?php
// controller da loja: responde sempre em JSON
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json; charset=utf-8');

spl_autoload_register(function ($classe) {
    $arquivo = __DIR__ . '/classes/' . $classe . '.php';
    if (file_exists($arquivo)) {
        require_once $arquivo;
    }
});

function responder(array $dados, int $status = 200): void
{
    http_response_code($status);
    echo json_encode($dados, JSON_UNESCAPED_UNICODE);
    exit;
}

// sem login: usa o usuário 1 (se um dia tiver login, ele vem da sessão)
$usuarioId = $_SESSION['id_usuario'] ?? 1;

$corpo = json_decode(file_get_contents('php://input'), true) ?? [];
$acao  = $_GET['acao'] ?? $corpo['acao'] ?? '';

try {
    $loja = new LojaModel();

    switch ($acao) {
        case 'listar':
            $mochila = $loja->mochila((int)$usuarioId);
            $itens = array_map(
                fn(Item $i) => $i->paraArray($mochila[$i->getId()] ?? 0),
                $loja->listarItens()
            );
            responder(['ok' => true, 'moeda' => $loja->saldo((int)$usuarioId), 'itens' => $itens]);

        case 'comprar':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                responder(['ok' => false, 'mensagem' => 'Método inválido.'], 405);
            }
            $r = $loja->comprar((int)$usuarioId, (int)($corpo['item_id'] ?? 0), (int)($corpo['quantidade'] ?? 1));
            responder(['ok' => true, 'mensagem' => $r['item'] . ' adicionado à mochila!', 'moeda' => $r['moeda'], 'qtd' => $r['qtd']]);

        default:
            responder(['ok' => false, 'mensagem' => 'Ação desconhecida.'], 400);
    }
} catch (SaldoInsuficienteException $e) {
    responder(['ok' => false, 'mensagem' => $e->getMessage()], 400);
} catch (ItemNaoEncontradoException $e) {
    responder(['ok' => false, 'mensagem' => $e->getMessage()], 404);
} catch (InvalidArgumentException $e) {
    responder(['ok' => false, 'mensagem' => $e->getMessage()], 400);
} catch (PDOException $e) {
    responder(['ok' => false, 'mensagem' => 'Erro no banco de dados.'], 500);
}