<?php

require_once __DIR__ . "/../models/Usuario.php";

class UsuarioController {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function renderizar($view, $dados = []) {
        extract($dados);
        require_once __DIR__ . "/../Views/usuario/$view.php";
    }

    public function cadastro() {
        $this->renderizar('cadastro');
    }

    public function login() {
        $this->renderizar('login');
    }

    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$this->validaPost($_POST)) {
            $this->redirecionarComErro('cadastro', 'Você deve preencher todos os campos.');
        }

        $email = trim($_POST['email'] ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->redirecionarComErro('cadastro', 'Por favor, insira um e-mail válido.');
        }

        try {
            $usuario = new Usuario(
                trim($_POST['nome'] ?? ''),
                $email,
                $_POST['senha'] ?? '' 
            );
            
            $usuario->salvar();
            
            $_SESSION['flash_success'] = 'Cadastro realizado com sucesso! Faça login.';
            header("Location: ?acao=login");
            exit;

        } catch (Exception $e) {
            $this->redirecionarComErro('cadastro', $e->getMessage());
        }
    }

    public function fazerLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$this->validaPost($_POST)) {
            $this->redirecionarComErro('login', 'Você deve preencher todos os campos.');
        }

        $loginInput = trim($_POST['email'] ?? $_POST['login'] ?? '');
        $senha = $_POST['senha'] ?? '';

        try {
          
            $user = Usuario::getByLoginOrEmail($loginInput);

            if ($user && password_verify($senha, $user->getSenha())) {
                session_regenerate_id(true);

                $_SESSION['usuario_id'] = $user->getId();
                $_SESSION['usuario_nome'] = $user->getNome();
                
                header("Location: ?acao=dashboard");
                exit;
            }

            $this->redirecionarComErro('login', 'Usuário/E-mail ou senha incorretos.');

        } catch (Exception $e) {
            $this->redirecionarComErro('login', 'Erro ao processar login: ' . $e->getMessage());
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

    private function validaPost($post) {
        if (empty($post)) {
            return false;
        }

        foreach ($post as $chave => $dado) {
           
            if ($chave === 'senha') {
                if (!isset($dado) || $dado === '') {
                    return false;
                }
            } else {
                if (!isset($dado) || trim($dado) === '') {
                    return false;
                }
            }
        }
        return true;
    }

    private function redirecionarComErro($acao, $mensagem) {
        $_SESSION['flash_error'] = $mensagem;
        header("Location: ?acao=$acao");
        exit;
    }
}