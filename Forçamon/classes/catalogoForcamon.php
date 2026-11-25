<?php
// As subclasses (Fogo, Agua...) ficam todas dentro deste arquivo, então o autoload não as encontra pelo nome.
require_once __DIR__ . '/ForcamonElemental.php';

class CatalogoForcamon
{
    // Dragofav evolui para o Rubizard (nome e imagem dele). Rubizard não evolui.
    // As outras evoluções ainda usam nome e imagem provisórios: troque 'nome' e 'img'
    // quando os personagens ficarem prontos. Se a imagem não existir, a arena usa a da forma base.
    public static function dados(): array
    {
        return [
    [
        'id' => 1,
        'nome' => 'Rubizard',
        'tipo' => 'fogo',
        'nivel' => 50,
        'hpMax' => 80,
        'ataque' => 84,
        'defesa' => 78,
        'velocidade' => 100,
        'fraquezas' => ['agua', 'smoke', 'alcohol'],
        'img' => 'imagens-personagens/rubizard.png',
        'evolucao' => null
    ],
    [
        'id' => 2,
        'nome' => 'Jynxtetoi',
        'tipo' => 'ice',
        'nivel' => 50,
        'hpMax' => 95,
        'ataque' => 50,
        'defesa' => 35,
        'velocidade' => 95,
        'fraquezas' => ['fogo', 'bug', 'rock', 'ghost', 'dark', 'steel', 'dvd'],
        'img' => 'imagens-personagens/jynxtetoi.png',
        'evolucao' => null
    ],
    [
        'id' => 3,
        'nome' => 'Meloetta',
        'tipo' => 'normal',
        'nivel' => 50,
        'hpMax' => 100,
        'ataque' => 77,
        'defesa' => 77,
        'velocidade' => 90,
        'fraquezas' => ['bug', 'dark'],
        'img' => 'imagens-personagens/meloetta.png',
        'evolucao' => null
    ],
    [
        'id' => 4,
        'nome' => 'Nilldrill',
        'tipo' => 'bug',
        'nivel' => 50,
        'hpMax' => 65,
        'ataque' => 90,
        'defesa' => 40,
        'velocidade' => 75,
        'fraquezas' => ['fogo', 'flying', 'ground', 'rock', 'psiquico'],
        'img' => 'imagens-personagens/nilldrill.png',
        'evolucao' => [
            'nome' => 'Nilldrill Evoluído',
            'bonusHp' => 30,
            'bonusAtaque' => 30,
            'bonusDefesa' => 20,
            'bonusVelocidade' => 20,
            'img' => 'imagens-personagens/nilldrill_evoluido.png'
        ]
    ],
    [
        'id' => 5,
        'nome' => 'Velostervroom',
        'tipo' => 'steel',
        'nivel' => 50,
        'hpMax' => 80,
        'ataque' => 119,
        'defesa' => 90,
        'velocidade' => 90,
        'fraquezas' => ['fogo', 'ground'],
        'img' => 'imagens-personagens/velostervroom.png',
        'evolucao' => null
    ],
    [
        'id' => 6,
        'nome' => 'Vianalax',
        'tipo' => 'normal',
        'nivel' => 50,
        'hpMax' => 160,
        'ataque' => 110,
        'defesa' => 65,
        'velocidade' => 30,
        'fraquezas' => ['luta', 'buzz'],
        'img' => 'imagens-personagens/vianalax.png',
        'evolucao' => [
            'nome' => 'Vianalax Evoluído',
            'bonusHp' => 40,
            'bonusAtaque' => 25,
            'bonusDefesa' => 25,
            'bonusVelocidade' => 10,
            'img' => 'imagens-personagens/vianalax_evoluido.png'
        ]
    ],
    [
        'id' => 7,
        'nome' => 'Violetwo',
        'tipo' => 'psiquico',
        'nivel' => 50,
        'hpMax' => 106,
        'ataque' => 110,
        'defesa' => 90,
        'velocidade' => 130,
        'fraquezas' => ['bug', 'ghost', 'dark'],
        'img' => 'imagens-personagens/violetwo.png',
        'evolucao' => null
    ],
    [
        'id' => 8,
        'nome' => 'Brrpatana',
        'tipo' => 'agua',
        'nivel' => 50,
        'hpMax' => 70,
        'ataque' => 52,
        'defesa' => 48,
        'velocidade' => 55,
        'fraquezas' => ['planta', 'electric', 'nico', 'rd'],
        'img' => 'imagens-personagens/brrpatana.png',
        'evolucao' => [
            'nome' => 'Brrpatana Evoluído',
            'bonusHp' => 35,
            'bonusAtaque' => 30,
            'bonusDefesa' => 25,
            'bonusVelocidade' => 20,
            'img' => 'imagens-personagens/brrpatana_evoluido.png'
        ]
    ],
    [
        'id' => 9,
        'nome' => 'Darkvi',
        'tipo' => 'dark',
        'nivel' => 50,
        'hpMax' => 70,
        'ataque' => 90,
        'defesa' => 90,
        'velocidade' => 125,
        'fraquezas' => ['luta', 'bug', 'fada', 'ruivas', 'donw'],
        'img' => 'imagens-personagens/darkvi.png',
        'evolucao' => null
    ],
    [
        'id' => 10,
        'nome' => 'Dragofav',
        'tipo' => 'dragao',
        'nivel' => 50,
        'hpMax' => 91,
        'ataque' => 134,
        'defesa' => 95,
        'velocidade' => 80,
        'fraquezas' => ['rock', 'ice', 'dragao', 'fada', 'jl'],
        'img' => 'imagens-personagens/dragofav.png',
        'evolucao' => [
            'nome' => 'Rubizard',
            'bonusHp' => 35,
            'bonusAtaque' => 25,
            'bonusDefesa' => 25,
            'bonusVelocidade' => 15,
            'img' => 'imagens-personagens/rubizard.png'
        ]
    ]
];
    }

    public static function criar(array $dados): Forcamon
    {
        return match ($dados['tipo']) {
            'fogo' => new ForcamonFogo($dados),
            'agua' => new ForcamonAgua($dados),
            'psiquico' => new ForcamonPsiquico($dados),
            default => new ForcamonGenerico($dados),
        };
    }

    // Forçamons que só existem como forma evoluída de outro (não podem ser escolhidos no time).
    public static function nomesSoPorEvolucao(): array
    {
        $nomes = array_column(self::dados(), 'nome');
        $alvos = [];
        foreach (self::dados() as $d) {
            $nomeEvolucao = $d['evolucao']['nome'] ?? '';
            if (in_array($nomeEvolucao, $nomes, true)) {
                $alvos[] = $nomeEvolucao;
            }
        }
        return $alvos;
    }

    public static function todos(): array
    {
        return array_map(fn(array $d) => self::criar($d), self::dados());
    }

    public static function buscar(int $id): ?Forcamon
    {
        foreach (self::dados() as $d) {
            if ((int)$d['id'] === $id) {
                return self::criar($d);
            }
        }
        return null;
    }
}