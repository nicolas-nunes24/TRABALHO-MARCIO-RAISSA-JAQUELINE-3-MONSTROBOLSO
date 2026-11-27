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
     * - Recebe um array associativo ($dados) com as informações do Forcamon.
     * - Inicializa todas as propriedades do objeto usando os valores fornecidos ou valores padrão caso algum dado não seja informado.
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
     * - Método abstrato que obriga as classes filhas a retornarem um array com os golpes do Forcamon.
     */
    abstract public function getGolpes(): array;

    /**
     * - Recebe o tipo do golpe sofrido ($tipoGolpe).
     * - Verifica se o tipo está na lista de fraquezas do Forcamon e retorna o multiplicador de dano (2.0 se for fraqueza, 1.0 caso contrário).
     */
    public function calcularMultiplicador(string $tipoGolpe): float
    {
        return in_array(strtolower($tipoGolpe), $this->fraquezas, true) ? 2.0 : 1.0;
    }

    /**
     * - Retorna o nome do Forcamon.
     */
    public function getNome(): string { return $this->nome; }

    /**
     * - Retorna o tipo do Forcamon.
     */
    public function getTipo(): string { return $this->tipo; }

    /**
     * - Retorna o valor atual de HP do Forcamon.
     */
    public function getHp(): int { return $this->hp; }

    /**
     * - Recebe o novo valor de HP ($hp).
     * - Atualiza o HP do Forcamon garantindo que o valor fique limitado entre 0 e o HP máximo.
     */
    public function setHp(int $hp): void { $this->hp = max(0, min($this->hpMax, $hp)); }

    /**
     * - Retorna a velocidade do Forcamon.
     */
    public function getVelocidade(): int { return $this->velocidade; }

    /**
     * - Retorna o ID único do Forcamon.
     */
    public function getId(): int { return $this->id; }

    /**
     * - Retorna o valor máximo de HP do Forcamon.
     */
    public function getVidaMaxima(): int { return $this->hpMax; }

    /**
     * - Verifica se o Forcamon ainda está vivo (HP maior que 0).
     */
    public function estaVivo(): bool { return $this->hp > 0; }

    /**
     * - Retorna se o Forcamon já passou pelo processo de evolução.
     */
    public function jaEvoluiu(): bool { return $this->evoluido; }

    /**
     * - Verifica se o Forcamon possui dados de evolução definidos e ainda não evoluiu.
     */
    public function podeEvoluir(): bool
    {
        return $this->evolucao !== null && !$this->evoluido;
    }

    /**
     * - Recebe a quantidade de HP a ser recuperada ($quantidade).
     * - Soma o valor ao HP atual usando setHp() para respeitar o limite máximo de vida.
     */
    public function curar(int $quantidade): void
    {
        $this->setHp($this->hp + $quantidade);
    }

    /**
     * - Remove qualquer efeito negativo, restaurando o status do Forcamon para 'Saudavel'.
     */
    public function curarStatus(): void
    {
        $this->status = 'Saudavel';
    }

    /**
     * - Valida se o Forcamon pode evoluir (lançando EvolucaoInvalidaException caso contrário), aplica o novo nome, imagem, bônus de atributos, restaura o HP ao máximo e marca como evoluído.
     */
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

    /**
     * - Converte todas as propriedades e golpes do Forcamon em um array associativo para serialização (como respostas JSON ou sessão).
     */
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