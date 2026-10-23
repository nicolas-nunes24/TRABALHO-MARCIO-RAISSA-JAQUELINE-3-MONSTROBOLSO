<?php
session_start();
$raiz = '../';
$paginaAtual = 'carta';

$pastaImagens = 'imagens-personagens/';

$tipos = [
    'fogo'     => 'Fogo',
    'agua'     => 'Água',
    'planta'   => 'Planta',
    'eletrico' => 'Elétrico',
    'normal'   => 'Normal',
];

$familias = [
    'Família do Fogo' => [
        ['nome' => 'Chamito',   'tipo' => 'fogo', 'imagem' => 'chamito.png',   'vida' => 40, 'ataque' => 12, 'defesa' => 8,
         'descricao' => 'Descreva aqui o primeiro estágio. Costuma ser encontrado perto de lugares quentes.'],
        ['nome' => 'Chamarrão', 'tipo' => 'fogo', 'imagem' => 'chamarrao.png', 'vida' => 70, 'ataque' => 20, 'defesa' => 14,
         'descricao' => 'Descreva aqui a primeira evolução e o que muda em relação ao Chamito.'],
        ['nome' => 'Infernão',  'tipo' => 'fogo', 'imagem' => 'infernao.png',  'vida' => 110, 'ataque' => 32, 'defesa' => 22,
         'descricao' => 'Descreva aqui a forma final e o seu poder.'],
    ],
    'Família da Água' => [
        ['nome' => 'Gotinha', 'tipo' => 'agua', 'imagem' => 'gotinha.png', 'vida' => 45, 'ataque' => 10, 'defesa' => 10,
         'descricao' => 'Descreva aqui o primeiro estágio desta família.'],
        ['nome' => 'Marolão', 'tipo' => 'agua', 'imagem' => 'marolao.png', 'vida' => 90, 'ataque' => 24, 'defesa' => 20,
         'descricao' => 'Descreva aqui a evolução final desta família.'],
    ],
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cartas - Forçamon</title>
    <link rel="stylesheet" href="<?= $raiz ?>css/site.css">
</head>
<body>

    <?php require __DIR__ . '/../includes/cabecalho.php'; ?>

    <main class="conteudo">
        <h1 class="pagina-titulo">Cartas</h1>
        <p class="pagina-subtitulo">
            Todos os Forçamões do jogo, com as suas evoluções. Siga as setas para ver como cada um evolui.
        </p>

        <?php foreach ($familias as $nomeFamilia => $cartas): ?>
            <section class="familia">
                <h2 class="familia-titulo"><?= htmlspecialchars($nomeFamilia) ?></h2>

                <div class="evolucoes">
                    <?php foreach ($cartas as $posicao => $carta): ?>
                        <?php if ($posicao > 0): ?>
                            <span class="seta-evolucao" aria-hidden="true">&#10140;</span>
                        <?php endif; ?>

                        <article class="carta tipo-<?= $carta['tipo'] ?>">
                            <header class="carta-topo">
                                <h3 class="carta-nome"><?= htmlspecialchars($carta['nome']) ?></h3>
                                <span class="carta-tipo"><?= $tipos[$carta['tipo']] ?></span>
                            </header>

                            <div class="carta-arte">
                                <?php if (is_file(__DIR__ . '/../' . $pastaImagens . $carta['imagem'])): ?>
                                    <img src="<?= $raiz . $pastaImagens . $carta['imagem'] ?>" alt="<?= htmlspecialchars($carta['nome']) ?>">
                                <?php else: ?>
                                    <img class="sem-imagem" src="<?= $raiz ?>pokebola.svg" alt="">
                                <?php endif; ?>
                            </div>

                            <p class="carta-descricao"><?= htmlspecialchars($carta['descricao']) ?></p>

                            <footer class="carta-status">
                                <span><strong><?= $carta['vida'] ?></strong>Vida</span>
                                <span><strong><?= $carta['ataque'] ?></strong>Ataque</span>
                                <span><strong><?= $carta['defesa'] ?></strong>Defesa</span>
                            </footer>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>
    </main>

</body>
</html>