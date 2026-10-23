<?php
    require_once __DIR__ . "/../../../../config/autoload.php";

$acao = $_GET['acao'] ?? 'cadastro';

$controller = new UsuarioController();

if (method_exists($controller, $acao)) {
    $controller->$acao();
} else {
    die("Ação '{$acao}' não encontrada.");
}