<?php

function conexao(){

    try {
        $db = new PDO("mysql:host=localhost; dbname=meu_banco; charset=utf8", "root", "");
        $db->setAttribute (PDO::ATTR_ERRMODE, PDO:: ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
        die("Erro na conexão" . $e->getMessage());
        }
        
        return $db;
}

?>