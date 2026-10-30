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

  
    <a href="<?= $raiz ?>index.php" class="logo-topo-esquerdo">
        <img class="logo-topo-img" src="<?= $raiz ?>Forcamon.png" alt="Forçamon">
    </a>

    <div class="auth-conteudo">
        <main class="card">
            
            <div class="card-header-img">
                <img src="<?= $raiz ?>faca_seu_login.png" alt="Faça seu Login">
            </div>

            <?php if (!empty($_SESSION['flash_error'])): ?>
                <div class="mensagem erro"><?= htmlspecialchars($_SESSION['flash_error']) ?></div>
                <?php unset($_SESSION['flash_error']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['flash_success'])): ?>
                <div class="mensagem sucesso"><?= htmlspecialchars($_SESSION['flash_success']) ?></div>
                <?php unset($_SESSION['flash_success']); ?>
            <?php endif; ?>

            <form class="form-auth" action="index.php?acao=fazerLogin" method="post">
                <input class="campo" type="text" name="email" placeholder="Usuário ou Email:" required>
                <input class="campo" type="password" name="senha" placeholder="Senha:" required>

                <button class="botao" type="submit">Entrar</button>

                <div class="form-rodape">
                    <a href="#" class="link-esqueci">Esqueci minha senha</a>
                    
                    <div class="divisor-linha"></div>

                    <div class="bloco-cadastro">
                        <span class="texto-cadastro">Não tem uma conta?</span>
                        <a href="index.php?acao=cadastro" class="link-cadastre-se">Cadastre-se</a>
                    </div>
                </div>
            </form>
        </main>
    </div>

</body>
</html>