<?php
class LojaModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Conexao::obter();
    }

    public function listarItens(): array
    {
        $linhas = $this->pdo->query('SELECT * FROM vw_loja_itens')->fetchAll();
        return array_map(fn($l) => Item::criar($l), $linhas);
    }

    public function mochila(int $idPerfil): array
    {
        $st = $this->pdo->prepare('SELECT id_item, quantidade FROM vw_mochila_usuario WHERE id_perfil = :p');
        $st->execute([':p' => $idPerfil]);
        return array_column($st->fetchAll(), 'quantidade', 'id_item');
    }

    public function saldo(int $idPerfil): int
    {
        $st = $this->pdo->prepare('SELECT moedas FROM Perfil WHERE id_perfil = :p');
        $st->execute([':p' => $idPerfil]);
        return (int)$st->fetchColumn();
    }

    public function comprar(int $idPerfil, int $idItem, int $quantidade = 1): array
    {
        if ($quantidade < 1) {
            throw new InvalidArgumentException('Quantidade inválida.');
        }

        $this->pdo->beginTransaction();
        try {
            $st = $this->pdo->prepare('SELECT nomeItem, valorItem FROM Loja WHERE id_item = :i');
            $st->execute([':i' => $idItem]);
            $item = $st->fetch();
            if (!$item) {
                throw new ItemNaoEncontradoException($idItem);
            }
            $total = (int)$item['valorItem'] * $quantidade;

            $st = $this->pdo->prepare('SELECT moedas FROM Perfil WHERE id_perfil = :p FOR UPDATE');
            $st->execute([':p' => $idPerfil]);
            $saldo = (int)$st->fetchColumn();
            if ($saldo < $total) {
                throw new SaldoInsuficienteException($saldo, $total);
            }

            $st = $this->pdo->prepare('UPDATE Perfil SET moedas = moedas - :t WHERE id_perfil = :p');
            $st->execute([':t' => $total, ':p' => $idPerfil]);

            $st = $this->pdo->prepare(
                'INSERT INTO Mochila (id_perfil, id_item, quantidade) VALUES (:p, :i, :q1)
                 ON DUPLICATE KEY UPDATE quantidade = quantidade + :q2'
            );
            $st->execute([':p' => $idPerfil, ':i' => $idItem, ':q1' => $quantidade, ':q2' => $quantidade]);

            $st = $this->pdo->prepare(
                'INSERT INTO LogCompra (id_perfil, id_item, quantidade, valorTotal) VALUES (:p, :i, :q, :t)'
            );
            $st->execute([':p' => $idPerfil, ':i' => $idItem, ':q' => $quantidade, ':t' => $total]);

            $this->pdo->commit();
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }

        return [
            'item'  => $item['nomeItem'],
            'moeda' => $saldo - $total,
            'qtd'   => $this->mochila($idPerfil)[$idItem] ?? $quantidade,
        ];
    }
}