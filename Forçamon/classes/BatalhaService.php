<?php
class BatalhaService
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Conexao::obter();
    }

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