<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar - Forçamon</title>
    <link rel="stylesheet" href="auth.css">
</head>
<body>

    <?php
    $raiz = '../../../../';
    $paginaAtual = 'login';
    require $raiz . 'includes/cabecalho.php';
    ?>

    <div class="auth-conteudo">
        <h1 class="logo" aria-label="Forçamon">Forçam<span class="logo-o"></span>n</h1>

        <main class="card">
            <nav class="abas">
                <a class="aba ativa" href="index.php?acao=login">Entrar</a>
                <a class="aba" href="index.php?acao=cadastro">Registrar</a>
            </nav>

            <?php if (!empty($_SESSION['flash_error'])): ?>
                <p class="mensagem erro"><?= htmlspecialchars($_SESSION['flash_error']) ?></p>
                <?php unset($_SESSION['flash_error']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['flash_success'])): ?>
                <p class="mensagem sucesso"><?= htmlspecialchars($_SESSION['flash_success']) ?></p>
                <?php unset($_SESSION['flash_success']); ?>
            <?php endif; ?>

            <form action="index.php?acao=fazerLogin" method="post">
                <input class="campo" type="email" name="email" placeholder="E-mail" aria-label="E-mail" autocomplete="email" required>
                <input class="campo" type="password" name="senha" placeholder="Senha" aria-label="Senha" autocomplete="current-password" required>
                <button class="botao" type="submit">Entrar</button>
            </form>
        </main>
    </div>

</body>
</html>