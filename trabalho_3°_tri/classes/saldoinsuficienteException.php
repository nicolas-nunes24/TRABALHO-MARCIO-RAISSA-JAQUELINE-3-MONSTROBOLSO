<?php
class SaldoInsuficienteException extends Exception
{
    public function __construct(int $saldo, int $preco)
    {
        parent::__construct("Saldo insuficiente: você tem {$saldo} e o item custa {$preco}.");
    }
}