<?php
class BatalhaService
{
    private PDO $pdo;

    /**
     * Recebe opcionalmente uma instância de conexão PDO ($pdo).
     * Inicializa o serviço utilizando a conexão informada ou obtém a conexão padrão caso nenhuma seja passada.
     */
    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Conexao::obter();
    }

    /**
     * Recebe o ID do perfil do jogador ($perfilId), o array com o time do jogador ($timeJogador),
     * o array com o time do oponente ($timeOponente), o resultado da partida ($resultado) e a quantidade de turnos ($turnos).
     * Calcula a recompensa em moedas, registra o log da batalha no banco de dados, atualiza as moedas e o contador de vitórias ou derrotas do perfil dentro de uma transação, e retorna um array com a recompensa ganha e o saldo atual de moedas.
     */
    public function registrarResultado(int $perfilId, array $timeJogador, array $timeOponente, string $resultado, int $turnos): array
    {
        $recompensa = $resultado === 'vitoria' ? 250 : ($resultado === 'derrota' ? 50 : 0);

        $this->pdo->beginTransaction();
        try {
            $st = $this->pdo->prepare(
                'INSERT INTO LogBatalha (id_perfil, time_jogador, time_oponente, resultado, turnos, moedas_ganhas)
                 VALUES (:p, :tj, :to, :r, :t, :m)'
            );
            $st->execute([
                ':p'  => $perfilId,
                ':tj' => implode(', ', $timeJogador),
                ':to' => implode(', ', $timeOponente),
                ':r'  => $resultado,
                ':t'  => max(1, $turnos),
                ':m'  => $recompensa,
            ]);

            if ($resultado === 'vitoria') {
                $upd = $this->pdo->prepare('UPDATE Perfil SET moedas = moedas + :m, vitorias = vitorias + 1 WHERE id_perfil = :p');
                $upd->execute([':m' => $recompensa, ':p' => $perfilId]);
            } elseif ($resultado === 'derrota') {
                $upd = $this->pdo->prepare('UPDATE Perfil SET moedas = moedas + :m, derrotas = derrotas + 1 WHERE id_perfil = :p');
                $upd->execute([':m' => $recompensa, ':p' => $perfilId]);
            }

            $saldo = $this->pdo->prepare('SELECT moedas FROM Perfil WHERE id_perfil = :p');
            $saldo->execute([':p' => $perfilId]);
            $moedasAtual = (int)$saldo->fetchColumn();

            $this->pdo->commit();
            return ['recompensa' => $recompensa, 'moedas' => $moedasAtual];
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}