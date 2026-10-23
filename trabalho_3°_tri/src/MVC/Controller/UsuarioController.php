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

    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$this->validaPost($_POST)) {
            $this->redirecionarComErro('cadastro', 'Você deve preencher todos os campos.');
        }

        try {
            // Passa os 3 parâmetros aceitos pelo construtor da Model (nome, email, senha)
            $usuario = new Usuario(
                $_POST['nome'] ?? '',
                $_POST['email'] ?? '',
                $_POST['senha'] ?? ''
            );
            
            $usuario->salvar();
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

        $email = trim($_POST['email']);
        $senha = $_POST['senha'];

        // Busca o usuário por email
        $user = Usuario::getByColumn($email, 'email');

        if ($user && password_verify($senha, $user->getSenha())) {
            $_SESSION['usuario_id'] = $user->getId();
            $_SESSION['usuario_nome'] = $user->getNome();
            
            header("Location: ?acao=dashboard");
            exit;
        }

        $this->redirecionarComErro('login', 'Usuário ou senha incorretos.');
    }

    private function validaPost($post) {
        if (empty($post)) {
            return false;
        }

        foreach ($post as $dado) {
            if (!isset($dado) || trim($dado) === '') {
                return false;
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