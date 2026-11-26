<?php
class ItemNaoEncontradoException extends Exception
{
    //recebe o id de um item e joga uma execao caso nao for encontrado
    public function __construct(int $id)
    {
        parent::__construct("O item {$id} não existe.");
    }
}