<?php
class PerfilInvalidoException extends Exception
{
    public function __construct()
    {
        parent::__construct("Perfil de treinador não encontrado.");
    }
}