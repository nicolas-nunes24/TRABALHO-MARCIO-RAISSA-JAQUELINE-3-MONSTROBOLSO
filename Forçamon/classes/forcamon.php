<?php
abstract class Forcamon
{
    protected int $id;
    protected string $nome;
    protected string $tipo;
    protected int $nivel;
    protected int $hp;
    protected int $hpMax;
    protected int $ataque;
    protected int $defesa;
    protected int $velocidade;
    protected array $fraquezas;
    protected string $status;
    protected string $img;
    protected ?array $evolucao;
    protected bool $evoluido;

    /**
     * Recebe um array associativo ($dados) com as informações do Forcamon.
     * Inicializa todas as propriedades do objeto usando os valores fornecidos ou valores padrão caso algum dado não seja informado.
     */
    public function __construct(array $dados)
    {
        $this->id = (int)($dados['id'] ?? 0);
        $this->nome = (string)$dados['nome'];
        $this->tipo = (string)$dados['tipo'];
        $this->nivel = (int)($dados['nivel'] ?? 50);
        $this->hpMax = (int)($dados['hpMax'] ?? 100);
        $this->hp = (int)($dados['hp'] ?? $this->hpMax);
        $this->ataque = (int)($dados['ataque'] ?? 50);
        $this->defesa = (int)($dados['defesa'] ?? 50);
        $this->velocidade = (int)($dados['velocidade'] ?? 50);
        $this->fraquezas = $dados['fraquezas'] ?? [];
        $this->status = (string)($dados['status'] ?? 'Saudavel');
        $this->img = (string)($dados['img'] ?? '');
        $this->evolucao = $dados['evolucao'] ?? null;
        $this->evoluido = (bool)($dados['evoluido'] ?? false);
    }

    /**
     * Não recebe parâmetros.
     * Método abstrato que obriga as classes filhas a retornarem um array com os golpes do Forcamon.
     */
    abstract public function getGolpes(): array;

    /**
     * Recebe o tipo do golpe sofrido ($tipoGolpe).
     * Verifica se o tipo está na lista de fraquezas do Forcamon e retorna o multiplicador de dano (2.0 se for fraqueza, 1.0 caso contrário).
     */
    public function calcularMultiplicador(string $tipoGolpe): float
    {
        return in_array(strtolower($tipoGolpe), $this->fraquezas, true) ? 2.0 : 1.0;
    }

    /**
     * Não recebe parâmetros.
     * Retorna o nome do Forcamon.
     */
    public function getNome(): string { return $this->nome; }

    /**
     * Não recebe parâmetros.
     * Retorna o tipo do Forcamon.
     */
    public function getTipo(): string { return $this->tipo; }

    /**
     * Não recebe parâmetros.
     * Retorna o valor atual de HP do Forcamon.
     */
    public function getHp(): int { return $this->hp; }

    /**
     * Recebe o novo valor de HP ($hp).
     * Atualiza o HP do Forcamon garantindo que o valor fique limitado entre 0 e o HP máximo.
     */
    public function setHp(int $hp): void { $this->hp = max(0, min($this->hpMax, $hp)); }

    /**
     * Não recebe parâmetros.
     * Retorna a velocidade do Forcamon.
     */
    public function getVelocidade(): int { return $this->velocidade; }

<<<<<<< HEAD
    /**
     * Não recebe parâmetros.
     * Converte e retorna todas as propriedades do Forcamon, incluindo seus golpes, em um array associativo.
     */
=======
    public function getId(): int { return $this->id; }
    public function getVidaMaxima(): int { return $this->hpMax; }
    public function estaVivo(): bool { return $this->hp > 0; }
    public function jaEvoluiu(): bool { return $this->evoluido; }

    public function podeEvoluir(): bool
    {
        return $this->evolucao !== null && !$this->evoluido;
    }

    public function curar(int $quantidade): void
    {
        $this->setHp($this->hp + $quantidade);
    }

    public function curarStatus(): void
    {
        $this->status = 'Saudavel';
    }

    public function evoluir(): void
    {
        if ($this->evolucao === null) {
            throw new EvolucaoInvalidaException("{$this->nome} não possui evolução.");
        }
        if ($this->evoluido) {
            throw new EvolucaoInvalidaException("{$this->nome} já evoluiu.");
        }

        $evo = $this->evolucao;
        $this->nome = $evo['nome'] !== '' ? $evo['nome'] : $this->nome;
        $this->img = $evo['img'] ?? $this->img;
        $this->hpMax += (int)($evo['bonusHp'] ?? 0);
        $this->ataque += (int)($evo['bonusAtaque'] ?? 0);
        $this->defesa += (int)($evo['bonusDefesa'] ?? 0);
        $this->velocidade += (int)($evo['bonusVelocidade'] ?? 0);
        $this->hp = $this->hpMax;
        $this->evoluido = true;
    }

>>>>>>> 13e3c02a66f83b4eeafc22cfc3b410a5341c1a10
    public function paraArray(): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'tipo' => $this->tipo,
            'nivel' => $this->nivel,
            'hp' => $this->hp,
            'hpMax' => $this->hpMax,
            'ataque' => $this->ataque,
            'defesa' => $this->defesa,
            'velocidade' => $this->velocidade,
            'fraquezas' => $this->fraquezas,
            'status' => $this->status,
            'img' => $this->img,
            'evolucao' => $this->evolucao,
            'evoluido' => $this->evoluido,
            'golpes' => $this->getGolpes(),
        ];
    }
}