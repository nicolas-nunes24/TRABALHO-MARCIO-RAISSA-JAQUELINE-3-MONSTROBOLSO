<?php
<<<<<<< HEAD
    require_once __DIR__ . "/../../../../config/autoload.php";
=======
>>>>>>> 79f7883b41261b29acac70dd18f86864678bb739

require_once __DIR__ . "/Controller/UsuarioController.php";

$acao = $_GET['acao'] ?? 'cadastro';

$controller = new UsuarioController();

if (method_exists($controller, $acao)) {
    $controller->$acao();
} else {
    die("Ação '{$acao}' não encontrada.");
}