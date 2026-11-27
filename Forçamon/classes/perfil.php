<?php
class Perfil
{
    private int $id;
    private string $nomeTreinador;
    private int $moedas;
    private Mochila $mochila;
    private array $time = [];

    /**
     * Recebe o ID do perfil ($id), o nome do treinador ($nomeTreinador) e a quantidade de moedas ($moedas).
     * Inicializa as propriedades do perfil e instancia uma nova Mochila associada ao ID informado.
     */
    public function __construct(int $id, string $nomeTreinador, int $moedas)
    {
        $this->id = $id;
        $this->nomeTreinador = $nomeTreinador;
        $this->moedas = $moedas;
        $this->mochila = new Mochila($id);
    }

    /**
     * Recebe uma instância de Forcamon ($forcamon).
     * Adiciona o Forcamon ao time do perfil caso o time ainda tenha menos de 3 integrantes.
     */
    public function adicionarAoTime(Forcamon $forcamon): void
    {
        if (count($this->time) < 3) {
            $this->time[] = $forcamon;
        }
    }

    /**   
     * Retorna o ID do perfil.
     */
    public function getId(): int { return $this->id; }

    /**
     * Retorna o nome do treinador.
     */
    public function getNome(): string { return $this->nomeTreinador; }

    /**
     * Retorna a quantidade atual de moedas do perfil.
     */
    public function getMoedas(): int { return $this->moedas; }

    /**
     * Retorna a instância da mochila associada ao perfil.
     */
    public function getMochila(): Mochila { return $this->mochila; }

    /**
     * Retorna o array contendo os Forcamons do time do perfil.
     */
    public function getTime(): array { return $this->time; }
}