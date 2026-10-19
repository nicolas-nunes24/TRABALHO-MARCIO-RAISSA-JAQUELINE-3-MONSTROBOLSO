<?php

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
    }
}