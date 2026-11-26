<?php
class LojaModel
{
    private PDO $pdo;

    /**
     * Recebe opcionalmente uma instância de conexão PDO ($pdo).
     * Inicializa o modelo utilizando a conexão informada ou obtém a conexão padrão caso nenhuma seja passada.
     */
    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Conexao::obter();
    }

    /**
     * Não recebe parâmetros.
     * Consulta todos os itens da loja na view vw_loja_itens, converte cada registro em um objeto Item e retorna o array resultante.
     */
    public function listarItens(): array
    {
        $linhas = $this->pdo->query('SELECT * FROM vw_loja_itens')->fetchAll();
        return array_map(fn($l) => Item::criar($l), $linhas);
    }

    /**
     * Recebe o ID do perfil do usuário ($idPerfil).
     * Consulta os itens e quantidades na mochila do perfil e retorna um array associativo indexado pelo ID do item com suas respectivas quantidades.
     */
    public function mochila(int $idPerfil): array
    {
        $st = $this->pdo->prepare('SELECT id_item, quantidade FROM vw_mochila_usuario WHERE id_perfil = :p');
        $st->execute([':p' => $idPerfil]);
        return array_column($st->fetchAll(), 'quantidade', 'id_item');
    }

    /**
     * Recebe o ID do perfil do usuário ($idPerfil).
     * Consulta no banco de dados e retorna o saldo atual de moedas do perfil como um número inteiro.
     */
    public function saldo(int $idPerfil): int
    {
        $st = $this->pdo->prepare('SELECT moedas FROM Perfil WHERE id_perfil = :p');
        $st->execute([':p' => $idPerfil]);
        return (int)$st->fetchColumn();
    }

    /**
     * Recebe o ID do perfil ($idPerfil), o ID do item ($idItem) e opcionalmente a quantidade desejada ($quantidade, padrão 1).
     * Valida a quantidade, executa uma transação verificando a existência do item e o saldo disponível, desconta as moedas, adiciona o item à mochila, registra o log da compra e retorna um array com o nome do item, o saldo restante e a quantidade atual do item na mochila.
     */
    public function comprar(int $idPerfil, int $idItem, int $quantidade = 20): array
    {
        if ($quantidade < 20) {
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