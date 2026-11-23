<?php

require_once __DIR__ . "/../Model/Usuario.php";
require_once __DIR__ . "/../../../config/config.php";

class UsuarioController {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function renderizar($view, $dados = []) {
        extract($dados);
        require_once __DIR__ . "/../View/Usuario/$view.php";
    }

    public function cadastro() {
        $this->renderizar('cadastro');
    }

    public function login() {
        $this->renderizar('login');
    }

    public function dashboard() {
        if (empty($_SESSION['usuario_id'])) {
            header("Location: ?acao=login");
            exit;
        }

        $usuario = Usuario::getByColumn($_SESSION['usuario_id'], 'id_usuario');
        if (!$usuario) {
            $this->logout();
        }

        $this->garantirPerfilAtivo((int)$usuario->getId(), $usuario->getNome());

        $db = conexao();
        $st = $db->prepare("SELECT moedas FROM Perfil WHERE id_perfil = :p");
        $st->execute([':p' => $_SESSION['id_perfil']]);
        $moedasPerfil = (int)$st->fetchColumn();

        $this->renderizar('../Loja/lojinha', ['moeda' => $moedasPerfil]);
    }

    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirecionarComErro('cadastro', 'Requisição inválida.');
        }

        $nome  = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';

        if (!$nome || !$email || !$senha) {
            $this->redirecionarComErro('cadastro', 'Preencha todos os campos.');
        }

        try {
            $usuario = new Usuario($nome, $email, $senha);

            if ($usuario->salvar()) {
                $db = conexao();
                $st = $db->prepare("INSERT INTO Perfil (id_usuario, nome_treinador, moedas) VALUES (:u, :n, 500)");
                $st->execute([':u' => $usuario->getId(), ':n' => $nome]);

                $_SESSION['flash_success'] = 'Conta criada com sucesso!';
                header("Location: ?acao=login");
                exit;
            }

            $this->redirecionarComErro('cadastro', 'Erro ao salvar os dados.');
        } catch (Exception $e) {
            $this->redirecionarComErro('cadastro', $e->getMessage());
        }
    }

    public function fazerLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirecionarComErro('login', 'Requisição inválida.');
        }

        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';

        if (!$email || !$senha) {
            $this->redirecionarComErro('login', 'Informe seu e-mail e senha.');
        }

        try {
            $user = Usuario::getByColumn(strtolower($email), 'email');
            if (!$user) {
                $user = Usuario::getByColumn($email, 'nome');
            }

            if ($user && password_verify($senha, $user->getSenha())) {
                session_regenerate_id(true);

                $_SESSION['usuario_id'] = $user->getId();
                $this->garantirPerfilAtivo((int)$user->getId(), $user->getNome());

                header("Location: ../../../../site_jogo.php");
                exit;
            }

            $this->redirecionarComErro('login', 'E-mail ou senha incorretos.');
        } catch (Exception $e) {
            $this->redirecionarComErro('login', 'Erro no login: ' . $e->getMessage());
        }
    }

    private function garantirPerfilAtivo(int $usuarioId, string $nomePadrao): void {
        $db = conexao();
        if (!empty($_SESSION['id_perfil'])) {
            $chk = $db->prepare("SELECT id_perfil, nome_treinador FROM Perfil WHERE id_perfil = :p AND id_usuario = :u");
            $chk->execute([':p' => $_SESSION['id_perfil'], ':u' => $usuarioId]);
            $atual = $chk->fetch(PDO::FETCH_ASSOC);
            if ($atual) {
                $_SESSION['usuario_nome'] = $atual['nome_treinador'];
                return;
            }
        }

        $st = $db->prepare("SELECT id_perfil, nome_treinador FROM Perfil WHERE id_usuario = :u ORDER BY id_perfil ASC LIMIT 1");
        $st->execute([':u' => $usuarioId]);
        $perfil = $st->fetch(PDO::FETCH_ASSOC);

        if (!$perfil) {
            $ins = $db->prepare("INSERT INTO Perfil (id_usuario, nome_treinador, moedas) VALUES (:u, :n, 500)");
            $ins->execute([':u' => $usuarioId, ':n' => $nomePadrao]);
            $_SESSION['id_perfil'] = (int)$db->lastInsertId();
            $_SESSION['usuario_nome'] = $nomePadrao;
        } else {
            $_SESSION['id_perfil'] = (int)$perfil['id_perfil'];
            $_SESSION['usuario_nome'] = $perfil['nome_treinador'];
        }
    }

    public function logout() {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        header("Location: ?acao=login");
        exit;
    }

    private function redirecionarComErro($acao, $mensagem) {
        $_SESSION['flash_error'] = $mensagem;
        header("Location: ?acao=$acao");
        exit;
    }
}