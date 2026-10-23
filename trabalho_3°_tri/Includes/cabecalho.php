<?php
$logado = !empty($_SESSION['usuario_id']);
$auth = $raiz . 'src/MVC/View/Usuario/index.php';

$menu = [
    'inicio'    => ['Início',    'site_jogo.php',         false],
    'criadores' => ['Criadores', 'paginas/criadores.php', true],
    'cartas'    => ['Cartas',    'paginas/cartas.php',    true],
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
        <?php if ($logado): ?>
            <span>Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?></span>
            <a class="menu-link" href="<?= $auth ?>?acao=logout">Sair</a>
        <?php else: ?>
            <a class="menu-link<?= $paginaAtual === 'login' ? ' ativo' : '' ?>" href="<?= $auth ?>?acao=login">Entrar</a>
            <a class="menu-link<?= $paginaAtual === 'cadastro' ? ' ativo' : '' ?>" href="<?= $auth ?>?acao=cadastro">Registrar</a>
        <?php endif; ?>
    </div>
</header>