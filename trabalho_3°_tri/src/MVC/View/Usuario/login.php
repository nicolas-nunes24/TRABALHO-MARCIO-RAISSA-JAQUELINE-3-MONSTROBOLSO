<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>

    <h2>Login</h2>

   
    <?php if (!empty($_SESSION['flash_error'])): ?>
        <p style="color: red;"><?= htmlspecialchars($_SESSION['flash_error']) ?></p>
        <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['flash_success'])): ?>
        <p style="color: green;"><?= htmlspecialchars($_SESSION['flash_success']) ?></p>
        <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>

    
    <form action="index.php?acao=fazerLogin" method="post">
        <div>
            <label for="email">E-mail:</label>
            
            <input type="email" id="email" name="email" placeholder="Digite seu e-mail" required>
        </div>

        <div>
            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
        </div>

        <button type="submit">Entrar</button>
    </form>

    
    <p>Não tem uma conta? <a href="index.php?acao=cadastro">Cadastre-se aqui</a>.</p>

</body>
</html>