<?php
session_start();
$raiz = '../';
$paginaAtual = 'criadore';

$criadores = [
    [
        'nome'  => '',
        'papel' => '',
        'bio'   => '',
        'foto'  => null,
    ],
    [
        'nome'  => '',
        'papel' => '',
        'bio'   => '',
        'foto'  => null,
    ],
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criadores - Forçamon</title>
    <link rel="stylesheet" href="<?= $raiz ?>css/site.css">
</head>
<body>

    <?php require __DIR__ . '/../includes/cabecalho.php'; ?>

    <main class="conteudo">
        <h1 class="pagina-titulo">Criadores</h1>
        <p class="pagina-subtitulo">
            Conheça quem criou o Forçamon. Conte aqui como o jogo nasceu e o que inspirou o projeto.
        </p>

        <section class="criadores">
            <?php foreach ($criadores as $criador): ?>
                <article class="criador">
                    <?php if ($criador['foto']): ?>
                        <img class="criador-avatar" src="<?= $raiz . htmlspecialchars($criador['foto']) ?>" alt="">
                    <?php else: ?>
                        <div class="criador-avatar"><?= mb_substr($criador['nome'], 0, 1) ?></div>
                    <?php endif; ?>

                    <h2 class="criador-nome"><?= htmlspecialchars($criador['nome']) ?></h2>
                    <p class="criador-papel"><?= htmlspecialchars($criador['papel']) ?></p>
                    <p class="criador-bio"><?= htmlspecialchars($criador['bio']) ?></p>
                </article>
            <?php endforeach; ?>
        </section>
    </main>

</body>
</html>