<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar - Forçamon</title>
    <link rel="stylesheet" href="auth.css">
</head>
<body>

    <?php
    $raiz = '../../../../';
    $paginaAtual = 'cadastro';
    require $raiz . 'includes/cabecalho.php';
    ?>

    <div class="auth-conteudo">
        <div class="logo-container">
            <img class="logo-img" src="<?= $raiz ?>Forcamon.png" alt="Forçamon">
        </div>

        <main class="card">
            <nav class="abas">
                <a class="aba" href="index.php?acao=login">Entrar</a>
                <a class="aba ativa" href="index.php?acao=cadastro">Registrar</a>
            </nav>

            <?php if (!empty($_SESSION['flash_error'])): ?>
                <div class="mensagem erro"><?= htmlspecialchars($_SESSION['flash_error']) ?></div>
                <?php unset($_SESSION['flash_error']); ?>
            <?php endif; ?>

            <form class="form-auth" action="index.php?acao=registrar" method="post">
                <input class="campo" type="text" name="nome" placeholder="Nome de usuário:" required>
                <input class="campo" type="email" name="email" placeholder="Email:" required>
                <input class="campo" type="password" name="senha" placeholder="Senha:" required>

                <button class="botao" type="submit">Registrar</button>

                <div class="form-rodape">
                    <div class="divisor-linha"></div>

                
                
                </div>
            </form>
        </main>
    </div>

</body>
</html>