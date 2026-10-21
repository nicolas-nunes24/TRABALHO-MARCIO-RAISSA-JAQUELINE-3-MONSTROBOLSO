<!DOCTYPE html>
<html lang="pt-BR">
<head>
   
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forçamon</title>
</head>
<body>

<fieldset>
    <legend><h2>FORÇAMON</h2></legend>
    <button type="button" onclick="jogar()">Jogar</button>

    <h1>Monte seu time</h1>
    <button type="button" onclick="TrocaTela()">Time</button>

    <!-- Novo Botão para a Forçadex -->
    <h1>Forçadex</h1>
    <button type="button" onclick="abrirForcadex()">Acessar Forçadex</button>

    <h1>Lojinha</h1>
    <button type="button" onclick="telaLojinha()">Loja</button>
</fieldset>

<div id="time">

</div>

<script>
function jogar(){
    window.location.href = "jogo.php";
}

function TrocaTela(){
    window.location.href = "monte_time.php";
}

// Função para abrir a Forçadex
function abrirForcadex(){
    window.location.href = "aba-personagens.html";
}

function telaLojinha(){
    window.location.href = "lojinha.php";
}
</script>
</body>
</html>