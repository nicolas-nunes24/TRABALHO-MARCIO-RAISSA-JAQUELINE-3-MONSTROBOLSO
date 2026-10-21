<?php

function conexao() {

    $host = 'localhost';
    $dbname = 'meu_jogo';
    $user = 'root';
    $pass = '';

    try {
        $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
        
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        return new PDO($dsn, $user, $pass, $options);

    } catch (PDOException $e) {
        die("Erro na conexão com o banco de dados.");
    }
}