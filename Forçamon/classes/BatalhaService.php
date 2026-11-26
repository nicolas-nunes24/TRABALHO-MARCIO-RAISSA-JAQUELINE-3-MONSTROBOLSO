<?php
require_once __DIR__ . '/../log/registrar_log.php';

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
        $resultadosValidos = ['vitoria', 'derrota', 'fuga'];
        if (!in_array($resultado, $resultadosValidos, true)) {
            $resultado = 'derrota';
        }

        $recompensa = match ($resultado) {
            'vitoria' => 250,
            'derrota' => 50,
            default   => 0,
        };

        $turnos = max(1, $turnos);
        $strJogador = implode(', ', $timeJogador);
        $strOponente = implode(', ', $timeOponente);

        $this->pdo->beginTransaction();
        try {
            $st = $this->pdo->prepare(
                'INSERT INTO LogBatalha (id_perfil, time_jogador, time_oponente, resultado, turnos, moedas_ganhas)
                 VALUES (:p, :tj, :to, :r, :t, :m)'
            );
            $st->execute([
                ':p'  => $perfilId,
                ':tj' => $strJogador,
                ':to' => $strOponente,
                ':r'  => $resultado,
                ':t'  => $turnos,
                ':m'  => $recompensa,
            ]);

            if ($resultado === 'vitoria') {
                $upd = $this->pdo->prepare('UPDATE Perfil SET moedas = moedas + :m, vitorias = vitorias + 1 WHERE id_perfil = :p');
                $upd->execute([':m' => $recompensa, ':p' => $perfilId]);
            } elseif ($resultado === 'derrota') {
                $upd = $this->pdo->prepare('UPDATE Perfil SET moedas = moedas + :m, derrotas = derrotas + 1 WHERE id_perfil = :p');
                $upd->execute([':m' => $recompensa, ':p' => $perfilId]);
            }

            $saldo = $this->pdo->prepare('SELECT nome_treinador, moedas FROM Perfil WHERE id_perfil = :p');
            $saldo->execute([':p' => $perfilId]);
            $perfil = $saldo->fetch();

            $moedasAtual = (int)($perfil['moedas'] ?? 0);
            $treinador = $perfil['nome_treinador'] ?? ($_SESSION['usuario_nome'] ?? 'Treinador');

            $this->pdo->commit();

            registrarLog(
                'batalha',
                "Resultado: {$resultado} | Turnos: {$turnos} | Time: [{$strJogador}] vs [{$strOponente}] | Prêmio: ₽ {$recompensa}",
                $treinador
            );

            return [
                'resultado'  => $resultado,
                'recompensa' => $recompensa,
                'moedas'     => $moedasAtual,
                'treinador'  => $treinador,
                'turnos'     => $turnos,
            ];
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function listarHistorico(int $perfilId, int $limite = 10): array
    {
        $st = $this->pdo->prepare(
            'SELECT id_batalha, time_jogador, time_oponente, resultado, turnos, moedas_ganhas, data_batalha
             FROM LogBatalha
             WHERE id_perfil = :p
             ORDER BY data_batalha DESC
             LIMIT :lim'
        );
        $st->bindValue(':p', $perfilId, PDO::PARAM_INT);
        $st->bindValue(':lim', $limite, PDO::PARAM_INT);
        $st->execute();
        return $st->fetchAll();
    }
}