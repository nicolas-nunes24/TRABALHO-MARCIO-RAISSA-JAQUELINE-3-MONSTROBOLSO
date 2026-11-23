<?php
class Mochila
{
    private int $idPerfil;
    private PDO $pdo;

    public function __construct(int $idPerfil)
    {
        $this->idPerfil = $idPerfil;
        $this->pdo = Conexao::obter();
    }

    public function listar(): array
    {
        $st = $this->pdo->prepare('SELECT * FROM vw_mochila_usuario WHERE id_perfil = :p');
        $st->execute([':p' => $this->idPerfil]);
        return $st->fetchAll();
    }

    public function consumir(int $idItem): array
    {
        $st = $this->pdo->prepare(
            'SELECT m.quantidade, l.* FROM Mochila m
             JOIN Loja l ON l.id_item = m.id_item
             WHERE m.id_perfil = :p AND m.id_item = :i'
        );
        $st->execute([':p' => $this->idPerfil, ':i' => $idItem]);
        $item = $st->fetch();

        if (!$item || (int)$item['quantidade'] <= 0) {
            throw new ItemNaoEncontradoException($idItem);
        }

        $upd = $this->pdo->prepare('UPDATE Mochila SET quantidade = quantidade - 1 WHERE id_perfil = :p AND id_item = :i');
        $upd->execute([':p' => $this->idPerfil, ':i' => $idItem]);

        return $item;
    }
}