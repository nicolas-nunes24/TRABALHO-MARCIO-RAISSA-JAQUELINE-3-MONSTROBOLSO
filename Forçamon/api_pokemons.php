<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/classes/autoload.php';
require_once __DIR__ . '/classes/ForcamonElemental.php';

$catalogoBruto = [
    [
        'id' => 1,
        'nome' => 'Rubizard',
        'tipo' => 'fogo',
        'nivel' => 50,
        'hpMax' => 80,
        'ataque' => 84,
        'defesa' => 78,
        'velocidade' => 100,
        'fraquezas' => ['agua', 'smoke', 'alcohol'],
        'img' => 'imagens-personagens/rubizard.png',
        'evolucao' => [
            'nome' => '',
            'bonusHp' => 35,
            'bonusAtaque' => 30,
            'bonusDefesa' => 20,
            'bonusVelocidade' => 15,
            'img' => 'imagens-personagens/rubizard.png'
        ]
    ],
    [
        'id' => 2,
        'nome' => 'Jynxtetoi',
        'tipo' => 'ice',
        'nivel' => 50,
        'hpMax' => 95,
        'ataque' => 50,
        'defesa' => 35,
        'velocidade' => 95,
        'fraquezas' => ['fogo', 'bug', 'rock', 'ghost', 'dark', 'steel', 'dvd'],
        'img' => 'imagens-personagens/jynxtetoi.png',
        'evolucao' => null
    ],
    [
        'id' => 3,
        'nome' => 'Meloetta',
        'tipo' => 'normal',
        'nivel' => 50,
        'hpMax' => 100,
        'ataque' => 77,
        'defesa' => 77,
        'velocidade' => 90,
        'fraquezas' => ['bug', 'dark'],
        'img' => 'imagens-personagens/meloetta.png',
        'evolucao' => null
    ],
    [
        'id' => 4,
        'nome' => 'Nilldrill',
        'tipo' => 'bug',
        'nivel' => 50,
        'hpMax' => 65,
        'ataque' => 90,
        'defesa' => 40,
        'velocidade' => 75,
        'fraquezas' => ['fogo', 'flying', 'ground', 'rock', 'psiquico'],
        'img' => 'imagens-personagens/nilldrill.png',
        'evolucao' => [
            'nome' => '',
            'bonusHp' => 30,
            'bonusAtaque' => 30,
            'bonusDefesa' => 20,
            'bonusVelocidade' => 20,
            'img' => 'imagens-personagens/nilldrill.png'
        ]
    ],
    [
        'id' => 5,
        'nome' => 'Velostervroom',
        'tipo' => 'steel',
        'nivel' => 50,
        'hpMax' => 80,
        'ataque' => 119,
        'defesa' => 90,
        'velocidade' => 90,
        'fraquezas' => ['fogo', 'ground'],
        'img' => 'imagens-personagens/velostervroom.png',
        'evolucao' => null
    ],
    [
        'id' => 6,
        'nome' => 'Vianalax',
        'tipo' => 'normal',
        'nivel' => 50,
        'hpMax' => 160,
        'ataque' => 110,
        'defesa' => 65,
        'velocidade' => 30,
        'fraquezas' => ['luta', 'buzz'],
        'img' => 'imagens-personagens/vianalax.png',
        'evolucao' => [
            'nome' => '',
            'bonusHp' => 40,
            'bonusAtaque' => 25,
            'bonusDefesa' => 25,
            'bonusVelocidade' => 10,
            'img' => 'imagens-personagens/vianalax.png'
        ]
    ],
    [
        'id' => 7,
        'nome' => 'Violetwo',
        'tipo' => 'psiquico',
        'nivel' => 50,
        'hpMax' => 106,
        'ataque' => 110,
        'defesa' => 90,
        'velocidade' => 130,
        'fraquezas' => ['bug', 'ghost', 'dark'],
        'img' => 'imagens-personagens/violetwo.png',
        'evolucao' => null
    ],
    [
        'id' => 8,
        'nome' => 'Brrpatana',
        'tipo' => 'agua',
        'nivel' => 50,
        'hpMax' => 70,
        'ataque' => 52,
        'defesa' => 48,
        'velocidade' => 55,
        'fraquezas' => ['planta', 'electric', 'nico', 'rd'],
        'img' => 'imagens-personagens/brrpatana.png',
        'evolucao' => [
            'nome' => '',
            'bonusHp' => 35,
            'bonusAtaque' => 30,
            'bonusDefesa' => 25,
            'bonusVelocidade' => 20,
            'img' => 'imagens-personagens/brrpatana.png'
        ]
    ],
    [
        'id' => 9,
        'nome' => 'Darkvi',
        'tipo' => 'dark',
        'nivel' => 50,
        'hpMax' => 70,
        'ataque' => 90,
        'defesa' => 90,
        'velocidade' => 125,
        'fraquezas' => ['luta', 'bug', 'fada', 'ruivas', 'donw'],
        'img' => 'imagens-personagens/darkvi.png',
        'evolucao' => null
    ],
    [
        'id' => 10,
        'nome' => 'Dragofav',
        'tipo' => 'dragao',
        'nivel' => 50,
        'hpMax' => 91,
        'ataque' => 134,
        'defesa' => 95,
        'velocidade' => 80,
        'fraquezas' => ['rock', 'ice', 'dragao', 'fada', 'jl'],
        'img' => 'imagens-personagens/dragofav.png',
        'evolucao' => [
            'nome' => '',
            'bonusHp' => 35,
            'bonusAtaque' => 25,
            'bonusDefesa' => 25,
            'bonusVelocidade' => 15,
            'img' => 'imagens-personagens/dragofav.png'
        ]
    ]
];

$pokemons = array_map(function (array $dados): array {
    $obj = match ($dados['tipo']) {
        'fogo' => new ForcamonFogo($dados),
        'agua' => new ForcamonAgua($dados),
        'psiquico' => new ForcamonPsiquico($dados),
        default => new ForcamonGenerico($dados),
    };
    return $obj->paraArray();
}, $catalogoBruto);

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