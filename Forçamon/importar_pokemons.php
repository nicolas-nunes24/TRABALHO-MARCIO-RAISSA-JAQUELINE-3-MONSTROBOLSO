<?php
//  Conexão com o banco (ou substitua pelo seu require_once 'conexao.php';)
$conn = new mysqli("localhost", "root", "", "meu_jogo");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// 2. Executa sua API localmente e captura a saída JSON sem erro de acento na URL ("Forçamon")
ob_start();
require_once __DIR__ . '/api_pokemons.php'; 
$jsonSaida = ob_get_clean();

// Volta o cabeçalho para HTML para exibir o resultado na tela
header('Content-Type: text/html; charset=utf-8');

$dadosApi = json_decode($jsonSaida, true);
$lista = $dadosApi['resultados'] ?? $pokemons ?? [];

if (empty($lista)) {
    die("Erro: não foi possível ler os Forçamons da API.");
}

// 3. Insere ou atualiza cada Forçamon no banco
$sql = "INSERT INTO forcamons (id, nome, tipo, nivel, hp_max, ataque, defesa, velocidade, fraquezas, img, evolucao)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            tipo = VALUES(tipo),
            nivel = VALUES(nivel),
            hp_max = VALUES(hp_max),
            ataque = VALUES(ataque),
            defesa = VALUES(defesa),
            velocidade = VALUES(velocidade),
            fraquezas = VALUES(fraquezas),
            img = VALUES(img),
            evolucao = VALUES(evolucao)";

$stmt = $conn->prepare($sql);
$totalSalvos = 0;

foreach ($lista as $p) {
    $id         = (int) $p['id'];
    $nome       = $p['nome'];
    $tipo       = $p['tipo'];
    $nivel      = (int) ($p['nivel'] ?? 50);
    $hpMax      = (int) ($p['hpMax'] ?? $p['hp'] ?? 100);
    $ataque     = (int) $p['ataque'];
    $defesa     = (int) $p['defesa'];
    $velocidade = (int) $p['velocidade'];
    $fraquezas  = json_encode($p['fraquezas'] ?? [], JSON_UNESCAPED_UNICODE);
    $img        = $p['img'];
    $evolucao   = !empty($p['evolucao']) ? json_encode($p['evolucao'], JSON_UNESCAPED_UNICODE) : null;

    $stmt->bind_param(
        "issiiiiisss",
        $id, $nome, $tipo, $nivel, $hpMax, $ataque, $defesa, $velocidade, $fraquezas, $img, $evolucao
    );

    if ($stmt->execute()) {
        $totalSalvos++;
    }
}

echo "<h3>Sucesso! {$totalSalvos} Forçamons foram salvos/atualizados no banco de dados.</h3>";

$stmt->close();
$conn->close();