<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <h2>Login</h2>

    <form action="cadastro.php" method="post">

        <label>Nome: </label>
        <input type="text" name="nome" required>

        <label>Senha: </label>
        <input type="text" name="senha" required>

        <input type="submit" name="btnSubmit">
    </form>
    <br>    <hr>

    <?php
        $nome = $_POST["nome"];

        echo $nome;        
    ?>
</body>
</html>