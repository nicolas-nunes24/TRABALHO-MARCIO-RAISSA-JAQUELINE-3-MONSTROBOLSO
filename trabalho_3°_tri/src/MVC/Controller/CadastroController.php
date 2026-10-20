<?php

<<<<<<< HEAD
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

        try {
            $usuario = new Usuario(
                $_POST['nome'] ?? '',
                $_POST['username'] ?? '',
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

        $username = trim($_POST['username']);
        $senha = $_POST['senha'];

        // Busca o objeto Usuario pelo username
        $user = Usuario::getByColumn($username, 'username');

        if ($user && password_verify($senha, $user->getSenha())) {
            $_SESSION['usuario_id'] = $user->getId();
            $_SESSION['usuario_nome'] = $user->getNome();
            $_SESSION['usuario_username'] = $user->getUsername();
            
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
=======
require_once "../config.php";
require_once "../models/Usuario.php";

class UsuarioController {

    /**
     * Auxiliar para respostas JSON com cabeçalho correto
     */
    private function jsonResponse(array $dados, int $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($dados, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Cadastra um novo usuário
     */
    public function cadastrar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->jsonResponse(['sucesso' => false, 'mensagem' => 'Método não permitido'], 405);
        }

        $nome  = $_POST['nome'] ?? null;
        $email = $_POST['email'] ?? null;
        $senha = $_POST['senha'] ?? null;

        try {
            $usuario = new Usuario($nome, $email, $senha);

            if ($usuario->cadastrar()) {
                return $this->jsonResponse([
                    'sucesso' => true,
                    'mensagem' => 'Usuário cadastrado com sucesso!',
                    'id' => $usuario->getId()
                ], 201);
            }

            return $this->jsonResponse(['sucesso' => false, 'mensagem' => 'Erro ao salvar no banco de dados'], 500);

        } catch (Exception $e) {
            return $this->jsonResponse(['sucesso' => false, 'mensagem' => $e->getMessage()], 400);
        }
    }

    /**
     * Busca dados de um usuário pelo ID
     */
    public function exibir($id) {
        if (!$id) {
            return $this->jsonResponse(['sucesso' => false, 'mensagem' => 'ID não informado'], 400);
        }

        $usuario = new Usuario();
        if ($usuario->load($id)) {
            return $this->jsonResponse([
                'sucesso' => true,
                'dados' => [
                    'id'     => $usuario->getId(),
                    'nome'   => $usuario->getNome(),
                    'email'  => $usuario->getEmail(),
                    'moedas' => $usuario->getMoedas()
                ]
            ]);
        }

        return $this->jsonResponse(['sucesso' => false, 'mensagem' => 'Usuário não encontrado'], 404);
    }

    /**
     * Atualiza dados de um usuário existente
     */
    public function atualizar($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'PUT') {
            return $this->jsonResponse(['sucesso' => false, 'mensagem' => 'Método não permitido'], 405);
        }

        $usuario = new Usuario();
        if (!$usuario->load($id)) {
            return $this->jsonResponse(['sucesso' => false, 'mensagem' => 'Usuário não encontrado'], 404);
        }

        try {
            $dados = $_POST;
            if (empty($dados)) {
                parse_str(file_get_contents("php://input"), $dados);
            }

            if (!empty($dados['nome'])) {
                $usuario->setNome($dados['nome']);
            }

            if (!empty($dados['email'])) {
                $usuario->setEmail($dados['email']);
            }

            if (!empty($dados['senha'])) {
                $usuario->setSenha($dados['senha']);
            }

            if (isset($dados['moedas'])) {
                $usuario->setMoedas((int)$dados['moedas']);
            }

            if ($usuario->cadastrar()) {
                return $this->jsonResponse(['sucesso' => true, 'mensagem' => 'Usuário atualizado com sucesso']);
            }

            return $this->jsonResponse(['sucesso' => false, 'mensagem' => 'Erro ao atualizar usuário'], 500);

        } catch (Exception $e) {
            return $this->jsonResponse(['sucesso' => false, 'mensagem' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove um usuário pelo ID
     */
    public function deletar($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            return $this->jsonResponse(['sucesso' => false, 'mensagem' => 'Método não permitido'], 405);
        }

        $usuario = new Usuario();
        if (!$usuario->load($id)) {
            return $this->jsonResponse(['sucesso' => false, 'mensagem' => 'Usuário não encontrado'], 404);
        }

        if ($usuario->delete()) {
            return $this->jsonResponse(['sucesso' => true, 'mensagem' => 'Usuário excluído com sucesso']);
        }

        return $this->jsonResponse(['sucesso' => false, 'mensagem' => 'Erro ao excluir usuário'], 500);
>>>>>>> 6391b5940150286d12520dfccf907e784d15924b
    }
}