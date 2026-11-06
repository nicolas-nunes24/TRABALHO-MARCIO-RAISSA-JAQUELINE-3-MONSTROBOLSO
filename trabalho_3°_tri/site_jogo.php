<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['usuario_id'])) {
    header("Location: src/MVC/View/Usuario/index.php?acao=login");
    exit;
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forçamon</title>
    <link rel="stylesheet" href="css/site.css">
</head>
<body>

<header class="cabecalho">
    <a class="cabecalho-logo" href="site_jogo.php">FORÇAM<span class="logo-o"></span>N</a>

    <nav class="cabecalho-menu">
        <a class="menu-link" href="jogo.php">Jogar</a>
        <a class="menu-link" href="monte_time.php">Time</a>
        <a class="menu-link" href="aba-personagens.html">Forçadex</a>
        <a class="menu-link" href="src/MVC/View/Usuario/index.php?acao=dashboard">Loja</a>
    </nav>

    <div class="cabecalho-usuario">
        <span>Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?></span>
        <a class="btn-sair" href="src/MVC/View/Usuario/index.php?acao=logout">Sair</a>
    </div>
</header>

<main class="conteudo">
    <div class="menu-jogo">
        <section class="opcao opcao-jogar">
            <div class="opcao-texto">
                <h2>Jogar</h2>
                <p>Enfrente um time sorteado de três personagens.</p>
                <button type="button" onclick="jogar()">Começar batalha</button>
            </div>
        </section>

        <section class="opcao opcao-time">
            <div class="opcao-texto">
                <h2>Seu time</h2>
                <p>Escolha quem entra na batalha.</p>
                <button type="button" onclick="TrocaTela()">Montar time</button>
            </div>
        </section>

        <section class="opcao opcao-forcadex">
            <div class="opcao-texto">
                <h2>Forçadex</h2>
                <p>Tipos, status e evoluções de cada personagem.</p>
                <button type="button" onclick="abrirForcadex()">Abrir Forçadex</button>
            </div>
        </section>

        <section class="opcao opcao-loja">
            <div class="opcao-texto">
                <h2>Lojinha</h2>
                <p>Itens para o seu time.</p>
                <button type="button" onclick="telaLojinha()">Ir à loja</button>
            </div>
        </section>
    </div>

</main>

<script>
function jogar(){
    window.location.href = "jogo.php";
}

function TrocaTela(){
    window.location.href = "monte_time.php";
}

function abrirForcadex(){
    window.location.href = "aba-personagens.html";
}

function telaLojinha(){
    window.location.href = "src/MVC/View/Usuario/index.php?acao=dashboard";
}
</script>
</body>
</html>