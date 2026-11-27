<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

// Golpes base vinculados automaticamente ao tipo de cada Pokémon
$bibliotecaGolpes = [
    'fogo'   => [
        ['nome' => 'Investida', 'poder' => 40, 'precisao' => 100, 'tipo' => 'normal', 'pp' => 35],
        ['nome' => 'Lança-Chamas', 'poder' => 90, 'precisao' => 100, 'tipo' => 'fogo', 'pp' => 15],
        ['nome' => 'Fumaça Densa', 'poder' => 0, 'precisao' => 100, 'tipo' => 'fogo', 'efeito' => 'ata', 'pp' => 20]
    ],
    'agua'   => [
        ['nome' => 'Investida', 'poder' => 40, 'precisao' => 100, 'tipo' => 'normal', 'pp' => 35],
        ['nome' => 'Hidro Bomba', 'poder' => 90, 'precisao' => 85, 'tipo' => 'agua', 'pp' => 10],
        ['nome' => 'Jacto Enfraquecedor', 'poder' => 40, 'precisao' => 100, 'tipo' => 'agua', 'efeito' => 'ata', 'pp' => 20]
    ],
    'planta' => [
        ['nome' => 'Investida', 'poder' => 40, 'precisao' => 100, 'tipo' => 'normal', 'pp' => 35],
        ['nome' => 'Chicote de Vinha', 'poder' => 80, 'precisao' => 100, 'tipo' => 'planta', 'pp' => 15],
        ['nome' => 'Pó de Esporos', 'poder' => 0, 'precisao' => 90, 'tipo' => 'planta', 'efeito' => 'def', 'pp' => 20]
    ],
    'normal' => [
        ['nome' => 'Investida', 'poder' => 40, 'precisao' => 100, 'tipo' => 'normal', 'pp' => 35],
        ['nome' => 'Golpe de Corpo', 'poder' => 85, 'precisao' => 100, 'tipo' => 'normal', 'pp' => 15],
        ['nome' => 'Encarar', 'poder' => 0, 'precisao' => 100, 'tipo' => 'normal', 'efeito' => 'def', 'pp' => 20]
    ],
    'ice'    => [
        ['nome' => 'Investida', 'poder' => 40, 'precisao' => 100, 'tipo' => 'normal', 'pp' => 35],
        ['nome' => 'Raio de Gelo', 'poder' => 90, 'precisao' => 100, 'tipo' => 'ice', 'pp' => 15],
        ['nome' => 'Vento Gelado', 'poder' => 35, 'precisao' => 95, 'tipo' => 'ice', 'efeito' => 'ata', 'pp' => 20]
    ],
    'dark'   => [
        ['nome' => 'Investida', 'poder' => 40, 'precisao' => 100, 'tipo' => 'normal', 'pp' => 35],
        ['nome' => 'Pulso Sombrio', 'poder' => 80, 'precisao' => 100, 'tipo' => 'dark', 'pp' => 15],
        ['nome' => 'Provocar', 'poder' => 0, 'precisao' => 100, 'tipo' => 'dark', 'efeito' => 'def', 'pp' => 20]
    ],
    'bug'    => [
        ['nome' => 'Investida', 'poder' => 40, 'precisao' => 100, 'tipo' => 'normal', 'pp' => 35],
        ['nome' => 'Zumbido', 'poder' => 90, 'precisao' => 100, 'tipo' => 'bug', 'pp' => 15],
        ['nome' => 'Fio de Teia', 'poder' => 0, 'precisao' => 95, 'tipo' => 'bug', 'efeito' => 'def', 'pp' => 20]
    ],
    'dragao' => [
        ['nome' => 'Investida', 'poder' => 40, 'precisao' => 100, 'tipo' => 'normal', 'pp' => 35],
        ['nome' => 'Garra Dragão', 'poder' => 80, 'precisao' => 100, 'tipo' => 'dragao', 'pp' => 15],
        ['nome' => 'Rugido Assustador', 'poder' => 0, 'precisao' => 100, 'tipo' => 'dragao', 'efeito' => 'ata', 'pp' => 20]
    ],
    'steel'  => [
        ['nome' => 'Investida', 'poder' => 40, 'precisao' => 100, 'tipo' => 'normal', 'pp' => 35],
        ['nome' => 'Cabeçada de Aço', 'poder' => 80, 'precisao' => 100, 'tipo' => 'steel', 'pp' => 15],
        ['nome' => 'Som Metálico', 'poder' => 0, 'precisao' => 100, 'tipo' => 'steel', 'efeito' => 'def', 'pp' => 20]
    ]
];

// Catálogo oficial dos 10 Pokémon do jogo
$pokemons = [
    [
        'id' => 1,
        'nome' => 'Rubizard',
        'tipo' => 'fogo',
        'nivel' => 50,
        'hpMax' => 80,
        'ataque' => 84,
        'defesa' => 78,
        'fraquezas' => ['agua', 'smoke', 'alcohol'],
        'img' => 'imagens-personagens/rubizard.png'
    ],
    [
        'id' => 2,
        'nome' => 'Jynxtetoi',
        'tipo' => 'ice',
        'nivel' => 50,
        'hpMax' => 95,
        'ataque' => 50,
        'defesa' => 35,
        'fraquezas' => ['fogo', 'bug', 'rock', 'ghost', 'dark', 'steel', 'dvd'],
        'img' => 'imagens-personagens/jynxtetoi.png'
    ],
    [
        'id' => 3,
        'nome' => 'Meloetta',
        'tipo' => 'normal',
        'nivel' => 50,
        'hpMax' => 100,
        'ataque' => 77,
        'defesa' => 77,
        'fraquezas' => ['bug', 'dark'],
        'img' => 'imagens-personagens/meloetta.png'
    ],
    [
        'id' => 4,
        'nome' => 'Nilldril',
        'tipo' => 'bug',
        'nivel' => 50,
        'hpMax' => 65,
        'ataque' => 90,
        'defesa' => 40,
        'fraquezas' => ['fogo', 'flying', 'ground', 'rock', 'psiquico'],
        'img' => 'imagens-personagens/nilldril.png'
    ],
    [
        'id' => 5,
        'nome' => 'Velostervroom',
        'tipo' => 'steel',
        'nivel' => 50,
        'hpMax' => 80,
        'ataque' => 119,
        'defesa' => 90,
        'fraquezas' => ['fogo', 'ground'],
        'img' => 'imagens-personagens/velostervroom.png'
    ],
    [
        'id' => 6,
        'nome' => 'Vianalax',
        'tipo' => 'normal',
        'nivel' => 50,
        'hpMax' => 160,
        'ataque' => 110,
        'defesa' => 65,
        'fraquezas' => ['luta', 'buzz'],
        'img' => 'imagens-personagens/vianalax.png'
    ],
    [
        'id' => 7,
        'nome' => 'Violetwo',
        'tipo' => 'planta',
        'nivel' => 50,
        'hpMax' => 106,
        'ataque' => 110,
        'defesa' => 90,
        'fraquezas' => ['bug', 'ghost', 'dark'],
        'img' => 'imagens-personagens/violetwo.png'
    ],
    [
        'id' => 8,
        'nome' => 'Brrpatana',
        'tipo' => 'agua',
        'nivel' => 50,
        'hpMax' => 70,
        'ataque' => 52,
        'defesa' => 48,
        'fraquezas' => ['planta', 'electric', 'nico', 'rd'],
        'img' => 'imagens-personagens/brrpatana.png'
    ],
    [
        'id' => 9,
        'nome' => 'Darkvi',
        'tipo' => 'dark',
        'nivel' => 50,
        'hpMax' => 70,
        'ataque' => 90,
        'defesa' => 90,
        'fraquezas' => ['luta', 'bug', 'fada', 'ruivas', 'donw'],
        'img' => 'imagens-personagens/darkvi.png'
    ],
    [
        'id' => 10,
        'nome' => 'Dragofav',
        'tipo' => 'dragao',
        'nivel' => 50,
        'hpMax' => 91,
        'ataque' => 134,
        'defesa' => 95,
        'fraquezas' => ['rock', 'ice', 'dragao', 'fada', 'jl'],
        'img' => 'imagens-personagens/dragofav.png'
    ]
];

// Anexa os golpes correspondentes a cada Pokémon
foreach ($pokemons as &$poke) {
    $poke['golpes'] = $bibliotecaGolpes[$poke['tipo']] ?? $bibliotecaGolpes['normal'];
}
unset($poke);

// 1. Busca individual por ID (?id=1) ou Nome (?nome=Rubizard)
if (isset($_GET['id']) || isset($_GET['nome'])) {
    $idBusca = isset($_GET['id']) ? (int) $_GET['id'] : null;
    $nomeBusca = isset($_GET['nome']) ? mb_strtolower(trim($_GET['nome'])) : null;

    foreach ($pokemons as $poke) {
        if (($idBusca && $poke['id'] === $idBusca) || ($nomeBusca && mb_strtolower($poke['nome']) === $nomeBusca)) {
            echo json_encode($poke, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            exit;
        }
    }

    http_response_code(404);
    echo json_encode(['erro' => 'Pokémon não encontrado'], JSON_UNESCAPED_UNICODE);
    exit;
}

// 2. Filtro por tipo (?tipo=fogo)
if (isset($_GET['tipo'])) {
    $tipoBusca = mb_strtolower(trim($_GET['tipo']));
    $filtrados = array_values(array_filter($pokemons, fn($p) => $p['tipo'] === $tipoBusca));

    echo json_encode([
        'total' => count($filtrados),
        'resultados' => $filtrados
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

// 3. Retorno padrão: lista completa
echo json_encode([
    'total' => count($pokemons),
    'resultados' => $pokemons
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

//sorteio do time inimigo
if (isset($_GET['sortear'])) {
    $qtd = max(1, min(count($pokemons), (int) $_GET['sortear']));
    $copia = $pokemons;
    shuffle($copia);

    echo json_encode([
        'total' => $qtd,
        'resultados' => array_slice($copia, 0, $qtd)
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}