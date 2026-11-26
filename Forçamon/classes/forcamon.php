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

    /**
     * Não recebe parâmetros.
     * Converte e retorna todas as propriedades do Forcamon, incluindo seus golpes, em um array associativo.
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
            'golpes' => $this->getGolpes(),
        ];
    }
}