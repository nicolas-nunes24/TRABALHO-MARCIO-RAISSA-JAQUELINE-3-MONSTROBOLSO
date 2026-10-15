<?php
require_once "../../Model/Usuario.php";

$db = conexao();

public function listarUsuarios(){
    $stmt = $this->db->prepare("SELECT * FROM usuario");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



?>