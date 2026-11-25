<?php
abstract class Item
{
    public function __construct(
        private int $id,
        private string $nome,
        private int $preco,
        private ?string $slug = null
    ) {}

    public function getId(): int { return $this->id; }
    public function getNome(): string { return $this->nome; }
    public function getPreco(): int { return $this->preco; }
    public function getSlug(): ?string { return $this->slug; }

    abstract public function categoria(): string;
    abstract public function descreverEfeito(): string;

    // monta o objeto certo conforme a linha da view vw_loja_itens
    public static function criar(array $l): Item
    {
        $id = (int)$l['id_item'];
        $preco = (int)$l['valorItem'];

        return match ($l['tipoItem']) {
            'hp'       => new ItemCura($id, $l['nomeItem'], $preco, $l['slugApi'],
                                       $l['valorEfeito'] !== null ? (int)$l['valorEfeito'] : null, $l['statusCura']),
            'status'   => new ItemStatus($id, $l['nomeItem'], $preco, $l['slugApi'], $l['statusCura']),
            'reviver'  => new ItemReviver($id, $l['nomeItem'], $preco, $l['slugApi']),
            'evolucao' => new ItemEvolucao($id, $l['nomeItem'], $preco, $l['slugApi']),
            default    => throw new InvalidArgumentException('Tipo de item inválido.'),
        };
    }

    public function paraArray(int $qtd = 0): array
    {
        return [
            'id'        => $this->id,
            'nome'      => $this->nome,
            'preco'     => $this->preco,
            'categoria' => $this->categoria(),
            'efeito'    => $this->descreverEfeito(),
            'slug'      => $this->slug,
            'qtd'       => $qtd,
        ];
    }
}

class ItemCura extends Item
{
    public function __construct(int $id, string $nome, int $preco, ?string $slug,
                                private ?int $valor, private ?string $statusCura)
    {
        parent::__construct($id, $nome, $preco, $slug);
    }

    public function categoria(): string { return 'hp'; }

    public function descreverEfeito(): string
    {
        $texto = $this->valor !== null ? "Recupera {$this->valor} HP" : 'Recupera 100% do HP';
        if ($this->statusCura === 'todos') {
            $texto .= ' e cura todas as condições';
        }
        return $texto;
    }
}

class ItemStatus extends Item
{
    public function __construct(int $id, string $nome, int $preco, ?string $slug,
                                private ?string $statusCura)
    {
        parent::__construct($id, $nome, $preco, $slug);
    }

    public function categoria(): string { return 'status'; }

    public function descreverEfeito(): string
    {
        return match ($this->statusCura) {
            'veneno'       => 'Cura envenenamento',
            'queimadura'   => 'Cura queimadura',
            'congelamento' => 'Cura congelamento',
            'sono'         => 'Cura sono',
            'paralisia'    => 'Cura paralisia',
            default        => 'Cura todas as condições',
        };
    }
}

class ItemReviver extends Item
{
    public function categoria(): string { return 'reviver'; }
    public function descreverEfeito(): string { return 'Revive um forçamon desmaiado com metade da vida'; }
}

class ItemEvolucao extends Item
{
    public function categoria(): string { return 'evolucao'; }
    public function descreverEfeito(): string { return 'Evolui um forçamon do seu time'; }
}