<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/classes/autoload.php';

$pokemons = array_map(fn(Forcamon $f) => $f->paraArray(), CatalogoForcamon::todos());

if (isset($_GET['sortear'])) {
    $qtd = max(1, min(count($pokemons), (int)$_GET['sortear']));
    $copia = $pokemons;
    shuffle($copia);
    echo json_encode([
        'total' => $qtd,
        'resultados' => array_slice($copia, 0, $qtd)
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

if (isset($_GET['id']) || isset($_GET['nome'])) {
    $idBusca = isset($_GET['id']) ? (int)$_GET['id'] : null;
    $nomeBusca = isset($_GET['nome']) ? mb_strtolower(trim($_GET['nome'])) : null;

    foreach ($pokemons as $poke) {
        if (($idBusca && $poke['id'] === $idBusca) || ($nomeBusca && mb_strtolower($poke['nome']) === $nomeBusca)) {
            echo json_encode($poke, JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    http_response_code(404);
    echo json_encode(['erro' => 'Forçamon não encontrado'], JSON_UNESCAPED_UNICODE);
    exit;
}

if (isset($_GET['tipo'])) {
    $tipoBusca = mb_strtolower(trim($_GET['tipo']));
    $filtrados = array_values(array_filter($pokemons, fn($p) => $p['tipo'] === $tipoBusca));
    echo json_encode([
        'total' => count($filtrados),
        'resultados' => $filtrados
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

echo json_encode([
    'total' => count($pokemons),
    'resultados' => $pokemons
], JSON_UNESCAPED_UNICODE);