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

 
    <a href="<?= $raiz ?>index.php" class="logo-topo-esquerdo">
        <img class="logo-topo-img" src="<?= $raiz ?>Forcamon.png" alt="Forçamon">
    </a>

    <div class="auth-conteudo">
        <main class="card">
       
            <div class="card-header-img">
                <img src="<?= $raiz ?>crie_sua_conta.png" alt="Crie sua Conta">
            </div>

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

                    <div class="bloco-cadastro">
                        <span class="texto-cadastro">Já tem uma conta?</span>
                        <a href="index.php?acao=login" class="link-cadastre-se">Faça Login</a>
                    </div>
                </div>
            </form>
        </main>
    </div>

</body>
</html>