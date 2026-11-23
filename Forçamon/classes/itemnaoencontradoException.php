<?php
class ItemNaoEncontradoException extends Exception
{
    public function __construct(int $id)
    {
        parent::__construct("O item {$id} não existe.");
    }
}