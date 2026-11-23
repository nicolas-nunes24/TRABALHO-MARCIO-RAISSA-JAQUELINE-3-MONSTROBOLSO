<?php
class SaldoInsuficienteException extends Exception
{
    public function __construct(int $saldo, int $total)
    {
        parent::__construct("Saldo insuficiente (₽ {$saldo} para ₽ {$total}).");
    }
}