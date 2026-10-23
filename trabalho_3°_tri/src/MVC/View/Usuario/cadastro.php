<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
</head>
<body>
    
    <h2>Cadastro</h2>

    <!-- Exibição de Mensagens de Erro -->
    <?php if (!empty($_SESSION['flash_error'])): ?>
        <div style="color: red; background-color: #f8d7da; padding: 10px; margin-bottom: 15px; border-radius: 4px;">
            <?= $_SESSION['flash_error']; ?>
        </div>
        <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>

    <!-- Exibição de Mensagens de Sucesso -->
    <?php if (!empty($_SESSION['flash_success'])): ?>
        <div style="color: green; background-color: #d4edda; padding: 10px; margin-bottom: 15px; border-radius: 4px;">
            <?= $_SESSION['flash_success']; ?>
        </div>
        <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>

    <form action="?acao=registrar" method="POST">

        <div>
            <label for="nome">Nome: </label>
            <input type="text" id="nome" name="nome" placeholder="Digite seu nome de usuário" required>
        </div>

        <br>

        <div>
            <label for="email">Email: </label>
            <input type="email" id="email" name="email" placeholder="Digite seu email" required>
        </div>

        <br>

        <div>
            <label for="senha">Senha: </label>
            <input type="password" id="senha" name="senha" placeholder="Digite sua senha (mín. 6 caracteres)" required>
        </div>

        <br>

        <input type="submit" name="btnSubmit" value="Registrar">
    </form>

    <br>
    <a href="?acao=login">Já tem uma conta? Faça login</a>

</body>
</html>