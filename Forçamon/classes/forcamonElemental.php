<?php
class ForcamonFogo extends Forcamon
{
    /**
     * Retorna um array com a lista de golpes específicos do Forcamon do tipo fogo.
     */
    public function getGolpes(): array
    {
        return [
            ['nome' => 'Investida', 'poder' => 40, 'precisao' => 100, 'tipo' => 'normal', 'pp' => 35],
            ['nome' => 'Lança-Chamas', 'poder' => 90, 'precisao' => 100, 'tipo' => 'fogo', 'pp' => 15],
            ['nome' => 'Fumaça Densa', 'poder' => 0, 'precisao' => 100, 'tipo' => 'fogo', 'efeito' => 'ata', 'pp' => 20],
        ];
    }
}

class ForcamonAgua extends Forcamon
{
    /**
     * Retorna um array com a lista de golpes específicos do Forcamon do tipo água.
     */
    public function getGolpes(): array
    {
        return [
            ['nome' => 'Investida', 'poder' => 40, 'precisao' => 100, 'tipo' => 'normal', 'pp' => 35],
            ['nome' => 'Hidro Bomba', 'poder' => 90, 'precisao' => 85, 'tipo' => 'agua', 'pp' => 10],
            ['nome' => 'Jato Enfraquecedor', 'poder' => 40, 'precisao' => 100, 'tipo' => 'agua', 'efeito' => 'ata', 'pp' => 20],
        ];
    }
}

class ForcamonPsiquico extends Forcamon
{
    /**
     * Retorna um array com a lista de golpes específicos do Forcamon do tipo psíquico.
     */
    public function getGolpes(): array
    {
        return [
            ['nome' => 'Investida', 'poder' => 40, 'precisao' => 100, 'tipo' => 'normal', 'pp' => 35],
            ['nome' => 'Psíquico', 'poder' => 90, 'precisao' => 100, 'tipo' => 'psiquico', 'pp' => 15],
            ['nome' => 'Barreira Mental', 'poder' => 0, 'precisao' => 100, 'tipo' => 'psiquico', 'efeito' => 'def', 'pp' => 20],
        ];
    }
}

class ForcamonGenerico extends Forcamon
{
    private static array $golpesPorTipo = [
        'normal' => [
            ['nome' => 'Investida', 'poder' => 40, 'precisao' => 100, 'tipo' => 'normal', 'pp' => 35],
            ['nome' => 'Golpe de Corpo', 'poder' => 85, 'precisao' => 100, 'tipo' => 'normal', 'pp' => 15],
            ['nome' => 'Encarar', 'poder' => 0, 'precisao' => 100, 'tipo' => 'normal', 'efeito' => 'def', 'pp' => 20],
        ],
        'ice' => [
            ['nome' => 'Investida', 'poder' => 40, 'precisao' => 100, 'tipo' => 'normal', 'pp' => 35],
            ['nome' => 'Raio de Gelo', 'poder' => 90, 'precisao' => 100, 'tipo' => 'ice', 'pp' => 15],
            ['nome' => 'Vento Gelado', 'poder' => 35, 'precisao' => 95, 'tipo' => 'ice', 'efeito' => 'ata', 'pp' => 20],
        ],
        'dark' => [
            ['nome' => 'Investida', 'poder' => 40, 'precisao' => 100, 'tipo' => 'normal', 'pp' => 35],
            ['nome' => 'Pulso Sombrio', 'poder' => 80, 'precisao' => 100, 'tipo' => 'dark', 'pp' => 15],
            ['nome' => 'Provocar', 'poder' => 0, 'precisao' => 100, 'tipo' => 'dark', 'efeito' => 'def', 'pp' => 20],
        ],
        'bug' => [
            ['nome' => 'Investida', 'poder' => 40, 'precisao' => 100, 'tipo' => 'normal', 'pp' => 35],
            ['nome' => 'Zumbido', 'poder' => 90, 'precisao' => 100, 'tipo' => 'bug', 'pp' => 15],
            ['nome' => 'Fio de Teia', 'poder' => 0, 'precisao' => 95, 'tipo' => 'bug', 'efeito' => 'def', 'pp' => 20],
        ],
        'dragao' => [
            ['nome' => 'Investida', 'poder' => 40, 'precisao' => 100, 'tipo' => 'normal', 'pp' => 35],
            ['nome' => 'Garra Dragão', 'poder' => 80, 'precisao' => 100, 'tipo' => 'dragao', 'pp' => 15],
            ['nome' => 'Rugido Assustador', 'poder' => 0, 'precisao' => 100, 'tipo' => 'dragao', 'efeito' => 'ata', 'pp' => 20],
        ],
        'steel' => [
            ['nome' => 'Investida', 'poder' => 40, 'precisao' => 100, 'tipo' => 'normal', 'pp' => 35],
            ['nome' => 'Cabeçada de Aço', 'poder' => 80, 'precisao' => 100, 'tipo' => 'steel', 'pp' => 15],
            ['nome' => 'Som Metálico', 'poder' => 0, 'precisao' => 100, 'tipo' => 'steel', 'efeito' => 'def', 'pp' => 20],
        ],
        'planta' => [
            ['nome' => 'Investida', 'poder' => 40, 'precisao' => 100, 'tipo' => 'normal', 'pp' => 35],
            ['nome' => 'Chicote de Vinha', 'poder' => 80, 'precisao' => 100, 'tipo' => 'planta', 'pp' => 15],
            ['nome' => 'Pó de Esporos', 'poder' => 0, 'precisao' => 90, 'tipo' => 'planta', 'efeito' => 'def', 'pp' => 20],
        ],
    ];

    /**
     * Retorna o array de golpes correspondente ao tipo do Forcamon ou, caso o tipo não esteja mapeado, retorna os golpes do tipo 'normal'.
     */
    public function getGolpes(): array
    {
        return self::$golpesPorTipo[$this->tipo] ?? self::$golpesPorTipo['normal'];
    }
}