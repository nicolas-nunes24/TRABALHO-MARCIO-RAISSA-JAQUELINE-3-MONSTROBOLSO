<?php

require_once __DIR__ . "/../Model/Usuario.php";

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

        $this->renderizar('../Loja/lojinha', ['moeda' => $usuario->getMoedas()]);
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

            if ($user && password_verify($senha, $user->getSenha())) {
                session_regenerate_id(true);

                $_SESSION['usuario_id'] = $user->getId();
                $_SESSION['usuario_nome'] = $user->getNome();
                
                header("Location: ../../../../site_jogo.php");
                exit;
            }

            $this->redirecionarComErro('login', 'E-mail ou senha incorretos.');
        } catch (Exception $e) {
            $this->redirecionarComErro('login', 'Erro no login: ' . $e->getMessage());
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