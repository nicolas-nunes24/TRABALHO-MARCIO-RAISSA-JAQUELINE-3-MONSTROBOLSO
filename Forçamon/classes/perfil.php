<?php
class Perfil
{
    private int $id;
    private string $nomeTreinador;
    private int $moedas;
    private Mochila $mochila;
    private array $time = [];

    public function __construct(int $id, string $nomeTreinador, int $moedas)
    {
        $this->id = $id;
        $this->nomeTreinador = $nomeTreinador;
        $this->moedas = $moedas;
        $this->mochila = new Mochila($id);
    }

    public function adicionarAoTime(Forcamon $forcamon): void
    {
        if (count($this->time) < 3) {
            $this->time[] = $forcamon;
        }
    }

    public function getId(): int { return $this->id; }
    public function getNome(): string { return $this->nomeTreinador; }
    public function getMoedas(): int { return $this->moedas; }
    public function getMochila(): Mochila { return $this->mochila; }
    public function getTime(): array { return $this->time; }
}