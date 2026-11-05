<?php
$raiz = '../../../../';
$paginaAtual = 'cadastro';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar - Forçamon</title>
    <link rel="stylesheet" href="auth.css">
</head>
<body>

    <main class="auth">
        <a class="logo" href="<?= $raiz ?>index.php">
            <img src="<?= $raiz ?>Forcamon.png" alt="Forçamon">
        </a>

        <div class="divisor">
            <img src="<?= $raiz ?>pokebola2.png" alt="">
        </div>

        <section class="card">
            <nav class="abas">
                <a href="index.php?acao=login" class="<?= $paginaAtual === 'login' ? 'ativa' : '' ?>">Entrar</a>
                <a href="index.php?acao=cadastro" class="<?= $paginaAtual === 'cadastro' ? 'ativa' : '' ?>">Registrar</a>
            </nav>

            <?php if (!empty($_SESSION['flash_error'])): ?>
                <div class="mensagem erro"><?= htmlspecialchars($_SESSION['flash_error']) ?></div>
                <?php unset($_SESSION['flash_error']); ?>
            <?php endif; ?>

            <form class="form" action="index.php?acao=registrar" method="post">
                <input class="campo" type="text" name="nome" placeholder="Nome de usuário:" required>
                <input class="campo" type="email" name="email" placeholder="Email:" required>
                <input class="campo" type="password" name="senha" placeholder="Senha:" required>
                <button class="botao" type="submit">Registrar</button>
            </form>
        </section>
    </main>

</body>
</html>