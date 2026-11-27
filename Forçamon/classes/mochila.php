<?php
class Mochila
{
    private int $idPerfil;
    private PDO $pdo;

    /**
     * Recebe o ID do perfil do usuário ($idPerfil).
     * Inicializa o objeto armazenando o ID do perfil e obtendo a instância padrão de conexão PDO com o banco de dados.
     */
    public function __construct(int $idPerfil)
    {
        $this->idPerfil = $idPerfil;
        $this->pdo = Conexao::obter();
    }

    /**
     * Consulta na view vw_mochila_usuario todos os itens pertencentes ao perfil atual e retorna o resultado como um array.
     */
    public function listar(): array
    {
        $st = $this->pdo->prepare('SELECT * FROM vw_mochila_usuario WHERE id_perfil = :p');
        $st->execute([':p' => $this->idPerfil]);
        return $st->fetchAll();
    }

    /**
     * Recebe o ID do item a ser consumido ($idItem).
     * Verifica se o item existe na mochila do perfil com quantidade maior que zero (lançando ItemNaoEncontradoException caso contrário), decrementa a quantidade em 1 no banco de dados e retorna um array com os dados do item.
     */
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