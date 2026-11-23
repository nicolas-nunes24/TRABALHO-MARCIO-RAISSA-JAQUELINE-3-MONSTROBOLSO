<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!empty($_SESSION['usuario_id'])) {
    header("Location: site_jogo.php");
    exit;
}

header("Location: src/MVC/View/Usuario/index.php?acao=login");
exit;