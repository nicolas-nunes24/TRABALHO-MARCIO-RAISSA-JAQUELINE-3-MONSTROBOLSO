<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <h2>Cadastro</h2>

    <form action="?acao=registrar" method="post">

        <label>Nome: </label>
        <input type="text" name="nome"  placeholder="Digite seu nome de usuario:" required>

        <label>Senha: </label>
        <input type="password" name="senha" placeholder="Digite sua senha:" required>

        <label>Email: </label>
        <input type="email" name="email" placeholder="Digite seu email:" required>

        <input type="submit" name="btnSubmit" value="Cadastrar">
    </form>
</body>
</html>