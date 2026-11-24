<?php
// 1. Conexão com o banco (ou use: require_once 'conexao.php';)
$conn = new mysqli("localhost", "root", "", "nome_do_seu_banco");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// 2. Buscar dados da sua API
// OPÇÃO A: Se a API responde em JSON pela URL local:
$json = file_get_contents("http://localhost/TRABALHO-MARCIO-RAISSA-JAQUELINE-3-MONSTROBOLSO/Forçamon/sua_api.php");
$listaPokemons = json_decode($json, true);

/*
// OPÇÃO B: Se a API for um arquivo PHP que apenas retorna ou define um array, comente as 2 linhas acima e use:
require_once "sua_api.php"; // caso ela crie a variável $listaPokemons
// ou: $listaPokemons = require "sua_api.php"; // caso ela use 'return [...]'
*/

if (!is_array($listaPokemons)) {
    die("Não foi possível ler os dados da API.");
}

// 3. Preparar o INSERT (INSERT IGNORE evita erro caso rode o script duas vezes)
$stmt = $conn->prepare("INSERT IGNORE INTO forcamons (nome, tipo, hp, imagem) VALUES (?, ?, ?, ?)");

$inseridos = 0;

foreach ($listaPokemons as $p) {
    // Ajuste as chaves ['nome'], ['tipo'], etc. conforme os nomes exatos da sua API
    $nome   = $p['nome'];
    $tipo   = $p['tipo'];
    $hp     = $p['hp'] ?? 100;
    $imagem = $p['img'] ?? $p['imagem']; // aceita 'img' ou 'imagem'

    $stmt->bind_param("ssis", $nome, $tipo, $hp, $imagem);
    if ($stmt->execute() && $stmt->affected_rows > 0) {
        $inseridos++;
    }
}

echo "Importação concluída! {$inseridos} Forçamons novos foram salvos no banco.";

$stmt->close();
$conn->close();
?>