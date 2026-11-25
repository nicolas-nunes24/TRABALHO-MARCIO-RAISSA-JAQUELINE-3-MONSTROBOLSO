<?php
class EvolucaoInvalidaException extends Exception
{
    public function __construct(string $motivo)
    {
        parent::__construct($motivo);
    }
}