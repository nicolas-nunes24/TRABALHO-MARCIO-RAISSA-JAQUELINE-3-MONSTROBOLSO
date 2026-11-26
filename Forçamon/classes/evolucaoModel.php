<?php
class EvolucaoModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Conexao::obter();
    }

    // Formas evoluídas que o perfil já desbloqueou, indexadas pelo id do forçamon.
    public function formasEvoluidas(int $idPerfil): array
    {
        $st = $this->pdo->prepare('SELECT id_forcamon FROM ForcamonEvoluido WHERE id_perfil = :p');
        $st->execute([':p' => $idPerfil]);

        $formas = [];
        foreach ($st->fetchAll() as $linha) {
            $forcamon = CatalogoForcamon::buscar((int)$linha['id_forcamon']);
            if ($forcamon === null || !$forcamon->podeEvoluir()) {
                continue;
            }
            $forcamon->evoluir();
            $formas[] = $forcamon->paraArray();
        }
        return $formas;
    }

    public function evoluir(int $idPerfil, int $idForcamon, int $idItem): array
    {
        $forcamon = CatalogoForcamon::buscar($idForcamon);
        if ($forcamon === null) {
            throw new EvolucaoInvalidaException('Forçamon não encontrado.');
        }

        $this->pdo->beginTransaction();
        try {
            $st = $this->pdo->prepare('SELECT 1 FROM ForcamonEvoluido WHERE id_perfil = :p AND id_forcamon = :f FOR UPDATE');
            $st->execute([':p' => $idPerfil, ':f' => $idForcamon]);
            if ($st->fetchColumn()) {
                throw new EvolucaoInvalidaException("{$forcamon->getNome()} já evoluiu.");
            }

            $forcamon->evoluir();

            $item = (new Mochila($idPerfil))->consumir($idItem);
            if ($item['tipoItem'] !== 'evolucao') {
                throw new EvolucaoInvalidaException('Esse item não serve para evoluir.');
            }

            $st = $this->pdo->prepare('INSERT INTO ForcamonEvoluido (id_perfil, id_forcamon) VALUES (:p, :f)');
            $st->execute([':p' => $idPerfil, ':f' => $idForcamon]);

            $this->pdo->commit();
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }

        return $forcamon->paraArray();
    }
}