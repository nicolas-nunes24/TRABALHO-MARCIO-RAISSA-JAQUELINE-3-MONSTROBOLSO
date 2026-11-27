<?php
date_default_timezone_set('America/Sao_Paulo');

/**
 * Recebe a categoria do log ($categoria), a mensagem a ser registrada ($mensagem) e opcionalmente o nome do usuário ($usuario, padrão null).
 * Inicia a sessão caso não esteja ativa, valida a categoria (usando 'geral' se não estiver na lista permitida), identifica o usuário e o IP, formata a linha com data e hora e grava o registro no arquivo de log correspondente.
 */
function registrarLog(string $categoria, string $mensagem, ?string $usuario = null): void {
    // Garante que a sessão esteja ativa para tentar pegar o usuário automaticamente
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Lista de arquivos permitidos (evita criação de arquivos inválidos)
    $categoriasValidas = ['registro', 'login', 'loja', 'itens', 'batalha', 'time'];
    $categoria = in_array($categoria, $categoriasValidas, true) ? $categoria : 'geral';

    // Cria a pasta 'logs' automaticamente se ainda não existir
    $pastaLogs = __DIR__;
    if (!is_dir($pastaLogs)) {
        mkdir($pastaLogs, 0755, true);
    }

    // Identifica o usuário (passado por parâmetro ou vindo da sessão)
    $user = $usuario
        ?? $_SESSION['usuario']
        ?? $_SESSION['nome']
        ?? $_SESSION['email']
        ?? 'Visitante';

    $dataHora = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'IP_DESCONHECIDO';

    $linha = "[{$dataHora}] [IP: {$ip}] [Usuário: {$user}] {$mensagem}" . PHP_EOL;

    // Salva em logs/registro.log, logs/login.log, logs/loja.log, etc.
    file_put_contents("{$pastaLogs}/{$categoria}.log", $linha, FILE_APPEND | LOCK_EX);
}