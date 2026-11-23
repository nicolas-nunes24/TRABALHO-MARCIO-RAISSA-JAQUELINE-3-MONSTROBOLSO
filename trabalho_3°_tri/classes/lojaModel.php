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

    // [id_item => quantidade]
    public function mochila(int $idUsuario): array
    {
        $st = $this->pdo->prepare('SELECT id_item, quantidade FROM vw_mochila_usuario WHERE id_usuario = :u');
        $st->execute([':u' => $idUsuario]);
        return array_column($st->fetchAll(), 'quantidade', 'id_item');
    }

    public function saldo(int $idUsuario): int
    {
        $st = $this->pdo->prepare('SELECT moedas FROM Usuario WHERE id_usuario = :u');
        $st->execute([':u' => $idUsuario]);
        return (int)$st->fetchColumn();
    }

    // quantidade é opcional: comprar($u, $i) compra 1
    public function comprar(int $idUsuario, int $idItem, int $quantidade = 1): array
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

            $st = $this->pdo->prepare('SELECT moedas FROM Usuario WHERE id_usuario = :u FOR UPDATE');
            $st->execute([':u' => $idUsuario]);
            $saldo = (int)$st->fetchColumn();
            if ($saldo < $total) {
                throw new SaldoInsuficienteException($saldo, $total);
            }

            $st = $this->pdo->prepare('UPDATE Usuario SET moedas = moedas - :t WHERE id_usuario = :u');
            $st->execute([':t' => $total, ':u' => $idUsuario]);

            $st = $this->pdo->prepare(
                'INSERT INTO Mochila (id_usuario, id_item, quantidade) VALUES (:u, :i, :q1)
                 ON DUPLICATE KEY UPDATE quantidade = quantidade + :q2'
            );
            $st->execute([':u' => $idUsuario, ':i' => $idItem, ':q1' => $quantidade, ':q2' => $quantidade]);

            $st = $this->pdo->prepare(
                'INSERT INTO LogCompra (id_usuario, id_item, quantidade, valorTotal) VALUES (:u, :i, :q, :t)'
            );
            $st->execute([':u' => $idUsuario, ':i' => $idItem, ':q' => $quantidade, ':t' => $total]);

            $this->pdo->commit();
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }

        return [
            'item'  => $item['nomeItem'],
            'moeda' => $saldo - $total,
            'qtd'   => $this->mochila($idUsuario)[$idItem] ?? $quantidade,
        ];
    }
}