<?php

class Item
{
    private int $id;
    private string $nome;
    private string $tipo;
    private ?int $valorEfeito;
    private ?string $statusCura;
    private int $preco;
    private string $slug;

    public function __construct(
        int $id,
        string $nome,
        string $tipo,
        ?int $valorEfeito,
        ?string $statusCura,
        int $preco,
        string $slug
    ) {
        $this->id          = $id;
        $this->nome        = $nome;
        $this->tipo        = $tipo;
        $this->valorEfeito = $valorEfeito;
        $this->statusCura  = $statusCura;
        $this->preco       = $preco;
        $this->slug        = $slug;
    }

    public static function criar(array $dados): self
    {
        return new self(
            (int)($dados['id_item'] ?? $dados['id'] ?? 0),
            (string)($dados['nomeItem'] ?? $dados['nome'] ?? ''),
            (string)($dados['tipoItem'] ?? $dados['tipo'] ?? 'hp'),
            isset($dados['valorEfeito']) ? (int)$dados['valorEfeito'] : null,
            $dados['statusCura'] ?? null,
            (int)($dados['valorItem'] ?? $dados['preco'] ?? 0),
            (string)($dados['slugApi'] ?? $dados['slug'] ?? 'potion')
        );
    }

    public function getId(): int { return $this->id; }
    public function getNome(): string { return $this->nome; }
    public function getTipo(): string { return $this->tipo; }
    public function getValorEfeito(): ?int { return $this->valorEfeito; }
    public function getStatusCura(): ?string { return $this->statusCura; }
    public function getPreco(): int { return $this->preco; }
    public function getSlug(): string { return $this->slug; }

    public function descricaoEfeito(): string
    {
        return match ($this->tipo) {
            'hp' => match (true) {
                $this->valorEfeito === null && $this->statusCura === 'todos' => 'Cura todo o HP e limpa status.',
                $this->valorEfeito === null                                  => 'Enche 100% da barra de HP.',
                default                                                      => "Recupera {$this->valorEfeito} de HP.",
            },
            'status' => $this->statusCura === 'todos'
                ? 'Cura todos os status negativos.'
                : 'Cura ' . ($this->statusCura ?? 'status') . '.',
            'reviver'  => 'Revive o Forçamon com metade do HP.',
            'evolucao' => 'Evolui o Forçamon na hora.',
            default    => '',
        };
    }

    public function __call(string $metodo, array $args)
    {
        if ($metodo === 'usar') {
            $alvo = $args[0] ?? null;
            $bonus = (int)($args[1] ?? 0);

            if (!$alvo instanceof Forcamon) {
                throw new InvalidArgumentException('Alvo inválido.');
            }

            if ($this->tipo === 'hp') {
                $cura = ($this->valorEfeito === null)
                    ? $alvo->getVidaMaxima()
                    : ($this->valorEfeito + $bonus);
                $alvo->curar($cura);
                return "{$this->nome} usado em {$alvo->getNome()}! (+{$cura} HP)";
            }

            if ($this->tipo === 'reviver' && !$alvo->estaVivo()) {
                $alvo->curar((int)($alvo->getVidaMaxima() * 0.5) + $bonus);
                return "{$alvo->getNome()} voltou pra luta!";
            }

            return "{$this->nome} aplicado em {$alvo->getNome()}.";
        }

        throw new BadMethodCallException("Método {$metodo} não existe.");
    }

    public function paraArray(int $qtdMochila = 0): array
    {
        return [
            'id'        => $this->id,
            'nome'      => $this->nome,
            'categoria' => $this->tipo,
            'efeito'    => $this->descricaoEfeito(),
            'preco'     => $this->preco,
            'slug'      => $this->slug,
            'qtd'       => $qtdMochila,
        ];
    }
}