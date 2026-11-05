<?php
$raiz = '../../../../';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar senha - Forçamon</title>
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
            <h1 class="card-titulo">Recuperar senha</h1>
            <p class="card-texto">Digite o email da sua conta. Se ele estiver cadastrado, enviaremos um link para você criar uma nova senha.</p>

            <?php if (!empty($_SESSION['flash_error'])): ?>
                <div class="mensagem erro"><?= htmlspecialchars($_SESSION['flash_error']) ?></div>
                <?php unset($_SESSION['flash_error']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['flash_success'])): ?>
                <div class="mensagem sucesso"><?= htmlspecialchars($_SESSION['flash_success']) ?></div>
                <?php unset($_SESSION['flash_success']); ?>
            <?php endif; ?>

            <form class="form" action="index.php?acao=enviarRecuperacao" method="post">
                <input class="campo" type="email" name="email" placeholder="Email:" required>
                <button class="botao" type="submit">Enviar link</button>
            </form>

            <a href="index.php?acao=login" class="link-esqueci">Voltar para o login</a>
        </section>
    </main>

</body>
</html>