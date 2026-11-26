<?php
class Conexao
{
    private static ?PDO $pdo = null;

    /**
     * Cria e configura uma única instância de conexão PDO com o banco de dados caso ainda não exista, e retorna essa instância.
     */
    public static function obter(): PDO
    {
        if (self::$pdo === null) {
            self::$pdo = new PDO(
                'mysql:host=localhost;dbname=meu_jogo;charset=utf8mb4',
                'root',
                '',
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        }
        return self::$pdo;
    }
}