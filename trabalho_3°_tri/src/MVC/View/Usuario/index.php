<?php
    require_once "/trabalho_3°_tri/config/autoload.php";

    $acao = $_GET['acao'] ?? 'cadastro';
    
    $nomeClasse = ucfirst(basename(__DIR__));
    $nomeController = $nomeClasse . "Controller";

    $controller = new $nomeController();

    $controller->$acao();
?>