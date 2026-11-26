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

    /**
     * Recebe o ID ($id), o nome ($nome), o tipo ($tipo), o valor do efeito ($valorEfeito),
     * o status que o item cura ($statusCura), o preço ($preco) e o slug ($slug).
     * Inicializa todas as propriedades do objeto Item com os valores informados.
     */
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

    /**
     * Recebe um array associativo ($dados) com as informações do item.
     * Cria e retorna uma nova instância da classe Item mapeando as chaves do array ou aplicando valores padrão.
     */
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

    /**
     * Não recebe parâmetros.
     * Retorna o ID do item.
     */
    public function getId(): int { return $this->id; }

    /**
     * Não recebe parâmetros.
     * Retorna o nome do item.
     */
    public function getNome(): string { return $this->nome; }

    /**
     * Não recebe parâmetros.
     * Retorna o tipo do item.
     */
    public function getTipo(): string { return $this->tipo; }

    /**
     * Não recebe parâmetros.
     * Retorna o valor numérico do efeito do item ou null caso não possua.
     */
    public function getValorEfeito(): ?int { return $this->valorEfeito; }

    /**
     * Não recebe parâmetros.
     * Retorna o nome do status que o item cura ou null caso não cure status.
     */
    public function getStatusCura(): ?string { return $this->statusCura; }

    /**
     * Não recebe parâmetros.
     * Retorna o preço do item.
     */
    public function getPreco(): int { return $this->preco; }

    /**
     * Não recebe parâmetros.
     * Retorna o slug identificador do item.
     */
    public function getSlug(): string { return $this->slug; }

    /**
     * Não recebe parâmetros.
     * Retorna uma string descrevendo o efeito do item com base no seu tipo, valor de efeito e status de cura.
     */
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

    /**
     * Recebe o nome do método chamado dinamicamente ($metodo) e um array com os argumentos passados ($args).
     * Intercepta chamadas ao método 'usar', valida se o alvo é um Forcamon, aplica o efeito correspondente (cura de HP, reviver ou genérico) e retorna uma mensagem de resultado; lança exceção se o método não existir ou se o alvo for inválido.
     */
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

    /**
     * Recebe opcionalmente a quantidade do item na mochila ($qtdMochila, padrão 0).
     * Converte e retorna os dados do item, incluindo a descrição do efeito e a quantidade na mochila, em um array associativo.
     */
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