<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar - Forçamon</title>
    <link rel="stylesheet" href="auth.css">
</head>
<body>

    <h1 class="logo" aria-label="Forçamon">Forçam<span class="logo-o"></span>n</h1>

    <main class="card">
        <nav class="abas">
            <a class="aba" href="index.php?acao=login">Entrar</a>
            <a class="aba ativa" href="index.php?acao=cadastro">Registrar</a>
        </nav>

        <?php if (!empty($_SESSION['flash_error'])): ?>
            <p class="mensagem erro"><?= htmlspecialchars($_SESSION['flash_error']) ?></p>
            <?php unset($_SESSION['flash_error']); ?>
        <?php endif; ?>

        <form action="index.php?acao=registrar" method="post">
            <input class="campo" type="text" name="nome" placeholder="Nome de usuário" aria-label="Nome de usuário" autocomplete="username" minlength="3" maxlength="20" required>
            <input class="campo" type="email" name="email" placeholder="E-mail" aria-label="E-mail" autocomplete="email" maxlength="120" required>
            <input class="campo" type="password" name="senha" placeholder="Senha (mínimo 6 caracteres)" aria-label="Senha" autocomplete="new-password" minlength="6" required>
            <button class="botao" type="submit">Registrar</button>
        </form>
    </main>

</body>
</html>