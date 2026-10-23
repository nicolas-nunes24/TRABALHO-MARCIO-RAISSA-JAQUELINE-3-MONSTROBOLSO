<?php

$menu = [
    'inicio'    => ['Início',    'site_jogo.php',           false],
    'criadores' => ['Criadores', 'paginas/criadores.php',   true],
    'cartas'    => ['Cartas',    'paginas/cartas.php',      true],
];
?>
<header class="cabecalho">
    <a class="cabecalho-logo" href="<?= $raiz ?>site_jogo.php" aria-label="Forçamon">Forçam<span class="logo-o"></span>n</a>

    <nav class="cabecalho-menu">
        <?php foreach ($menu as $chave => [$titulo, $destino, $temSeta]): ?>
            <a class="menu-link<?= $temSeta ? ' com-seta' : '' ?><?= $chave === $paginaAtual ? ' ativo' : '' ?>"
               href="<?= $raiz . $destino ?>"><?= $titulo ?></a>
        <?php endforeach; ?>
    </nav>

    <div class="cabecalho-usuario">
        <span>Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?></span>
        <a class="menu-link" href="<?= $raiz ?>src/MVC/View/Usuario/index.php?acao=logout">Sair</a>
    </div>
</header>