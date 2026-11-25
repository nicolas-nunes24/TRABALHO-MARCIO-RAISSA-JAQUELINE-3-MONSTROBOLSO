<?php
$raiz = '../../../../';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar senha - Forçamon</title>
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
      * { box-sizing: border-box; margin: 0; padding: 0; }
      body {
        min-height: 100vh;
        font-family: 'Nunito', sans-serif;
        font-weight: 700;
        color: #fff;
        background-color: #0a1330;
        background-image: linear-gradient(rgba(6, 11, 28, 0.84), rgba(6, 11, 28, 0.92)), url('<?= $raiz ?>imagens_cenarios/bg-pokemon.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 24px 16px;
      }

      .logo-jogo {
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Lilita One', sans-serif;
        font-size: 3.4rem;
        font-style: italic;
        line-height: 1;
        color: #ffcb05;
        -webkit-text-stroke: 7px #3563b8;
        paint-order: stroke fill;
        text-decoration: none;
        filter: drop-shadow(0 5px 0 rgba(0, 0, 0, 0.5));
        margin-bottom: 14px;
      }
      .logo-jogo svg {
        width: 52px;
        height: 52px;
        margin: 0 3px 0 2px;
      }

      .divisor {
        display: flex;
        align-items: center;
        gap: 14px;
        width: 100%;
        max-width: 320px;
        margin-bottom: 28px;
      }
      .divisor::before,
      .divisor::after {
        content: "";
        flex: 1;
        height: 2px;
        background: linear-gradient(to var(--lado), #ffcb05, transparent);
      }
      .divisor::before { --lado: left; }
      .divisor::after { --lado: right; }
      .divisor img {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        border: 2px solid #fff;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.6);
      }

      .painel-auth {
        position: relative;
        width: 100%;
        max-width: 440px;
        background: rgba(10, 20, 50, 0.92);
        border: 3px solid #fff;
        border-bottom: 5px solid #ffcb05;
        border-radius: 18px;
        padding: 40px 28px 26px;
        box-shadow: 0 18px 45px rgba(0, 0, 0, 0.75);
      }

      .aba-titulo {
        position: absolute;
        top: -24px;
        left: 24px;
        background: #12285a;
        border: 3px solid #fff;
        border-bottom: 4px solid #ffcb05;
        border-radius: 12px;
        padding: 4px 22px 2px;
        font-family: 'Lilita One', sans-serif;
        font-size: 1.55rem;
        color: #ffcb05;
        text-shadow: 0 2px 0 rgba(0, 0, 0, 0.5);
      }

      .card-texto {
        font-size: 0.96rem;
        line-height: 1.45;
        color: #d4def7;
        margin-bottom: 18px;
      }

      .form {
        display: flex;
        flex-direction: column;
        gap: 14px;
      }

      .campo {
        width: 100%;
        padding: 13px 16px;
        font-family: 'Nunito', sans-serif;
        font-weight: 700;
        font-size: 1rem;
        color: #fff;
        background: #060b1c;
        border: 3px solid #fff;
        border-radius: 11px;
        outline: none;
        transition: border-color 0.15s;
      }
      .campo::placeholder { color: #7584b0; }
      .campo:focus { border-color: #ffcb05; }

      .botao {
        margin-top: 4px;
        padding: 12px 20px;
        font-family: 'Lilita One', sans-serif;
        font-size: 1.2rem;
        letter-spacing: 0.4px;
        color: #12285a;
        background: #ffcb05;
        border: none;
        border-radius: 11px;
        box-shadow: 0 5px 0 #c79a00;
        cursor: pointer;
        transition: transform 0.08s, box-shadow 0.08s;
      }
      .botao:hover { background: #ffd83a; }
      .botao:active {
        transform: translateY(4px);
        box-shadow: 0 1px 0 #c79a00;
      }

      .voltar-box {
        margin-top: 18px;
        text-align: center;
      }
      .link-voltar {
        display: inline-block;
        font-family: 'Lilita One', sans-serif;
        font-size: 1rem;
        color: #fff;
        text-decoration: none;
        background: #1d3a8a;
        border: 2px solid #fff;
        border-radius: 9px;
        padding: 7px 20px;
        box-shadow: 0 3px 0 #0a1a4d;
      }
      .link-voltar:hover {
        border-color: #ffcb05;
        color: #ffcb05;
      }

      .mensagem {
        margin-bottom: 14px;
        padding: 10px 14px;
        font-size: 0.92rem;
        font-weight: 800;
        text-align: center;
        border-radius: 10px;
        border: 2px solid;
      }
      .mensagem.erro {
        color: #fecaca;
        background: rgba(153, 27, 27, 0.45);
        border-color: #f87171;
      }
      .mensagem.sucesso {
        color: #bbf7d0;
        background: rgba(21, 128, 61, 0.45);
        border-color: #4ade80;
      }

      @media (max-width: 480px) {
        .logo-jogo { font-size: 2.5rem; -webkit-text-stroke-width: 5px; }
        .logo-jogo svg { width: 38px; height: 38px; }
        .painel-auth { padding: 36px 18px 22px; }
        .aba-titulo { font-size: 1.35rem; }
      }
    </style>
</head>
<body>

    <a class="logo-jogo" href="<?= $raiz ?>index.php">
        <span>FORÇAM</span>
        <svg viewBox="0 0 48 48"><circle cx="24" cy="24" r="21" fill="#3563b8"/><circle cx="24" cy="24" r="17" fill="#fff" stroke="#1a2a55" stroke-width="3"/><path d="M7 24a17 17 0 0 1 34 0z" fill="#e8312f" stroke="#1a2a55" stroke-width="3" stroke-linejoin="round"/><line x1="7" y1="24" x2="41" y2="24" stroke="#1a2a55" stroke-width="3.5"/><circle cx="24" cy="24" r="6" fill="#fff" stroke="#1a2a55" stroke-width="3"/></svg>
        <span>N</span>
    </a>

    <div class="divisor">
        <img src="<?= $raiz ?>imagens_cenarios/pokebola2.png" alt="">
    </div>

    <section class="painel-auth">
        <div class="aba-titulo">Recuperar Senha</div>

        <p class="card-texto">Digite o e-mail da sua conta de treinador. Se ele estiver cadastrado, enviaremos um link para você criar uma nova senha.</p>

        <?php if (!empty($_SESSION['flash_error'])): ?>
            <div class="mensagem erro"><?= htmlspecialchars($_SESSION['flash_error']) ?></div>
            <?php unset($_SESSION['flash_error']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['flash_success'])): ?>
            <div class="mensagem sucesso"><?= htmlspecialchars($_SESSION['flash_success']) ?></div>
            <?php unset($_SESSION['flash_success']); ?>
        <?php endif; ?>

        <form class="form" action="index.php?acao=enviarRecuperacao" method="post">
            <input class="campo" type="email" name="email" placeholder="Seu e-mail cadastrado..." required>
            <button class="botao" type="submit">Enviar Link de Recuperação</button>
        </form>

        <div class="voltar-box">
            <a href="index.php?acao=login" class="link-voltar">Voltar para o Login</a>
        </div>
    </section>

</body>
</html>