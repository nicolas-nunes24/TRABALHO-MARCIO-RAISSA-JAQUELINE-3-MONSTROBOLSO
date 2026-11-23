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

    abstract public function getGolpes(): array;

    public function calcularMultiplicador(string $tipoGolpe): float
    {
        return in_array(strtolower($tipoGolpe), $this->fraquezas, true) ? 2.0 : 1.0;
    }

    public function getNome(): string { return $this->nome; }
    public function getTipo(): string { return $this->tipo; }
    public function getHp(): int { return $this->hp; }
    public function setHp(int $hp): void { $this->hp = max(0, min($this->hpMax, $hp)); }
    public function getVelocidade(): int { return $this->velocidade; }

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