<?php
$raiz = '../../../../';
$paginaAtual = 'login';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar - Forçamon</title>
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

            <?php if (!empty($_SESSION['flash_success'])): ?>
                <div class="mensagem sucesso"><?= htmlspecialchars($_SESSION['flash_success']) ?></div>
                <?php unset($_SESSION['flash_success']); ?>
            <?php endif; ?>

            <form class="form" action="index.php?acao=fazerLogin" method="post">
                <input class="campo" type="text" name="email" placeholder="Usuário ou Email:" required>
                <input class="campo" type="password" name="senha" placeholder="Senha:" required>
                <button class="botao" type="submit">Entrar</button>
            </form>

            <a href="index.php?acao=recuperar" class="link-esqueci">Esqueci minha senha</a>
        </section>
    </main>

</body>
</html>