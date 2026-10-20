<?php

require_once "../config.php";

class Usuario {

    private $id;
    private $nome;
    private $email;
    private $senha;
    private $moedas;

    public function __construct(string $nome = null, string $email = null, string $senha = null) {
        if ($nome === null && $email === null && $senha === null) return;
        
        $this->setNome($nome);
        $this->setEmail($email);
        $this->setSenha($senha);
        $this->moedas = 0;
    }


    public static function getByColumn($valor, $coluna) {
        $db = conexao();

        $colunasPermitidas = ['id_usuario', 'nome', 'username', 'email'];

    private static function getByColumn($valor, $coluna) {
        $db = conexao();

        $colunasPermitidas = ['id_usuario', 'nome', 'email'];

        if (!in_array($coluna, $colunasPermitidas)) {
            return false;
        }

        $sql = "SELECT * FROM Usuario WHERE {$coluna} = :valor LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->execute([':valor' => $valor]);

        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$dados) {
            return null;
        }

        // Instancia e hidrata o objeto Usuario para retornar ao Controller
        $usuario = new self();
        $usuario->id       = $dados['id_usuario'];
        $usuario->nome     = $dados['nome'];
        $usuario->username = $dados['username'];
        $usuario->email    = $dados['email'];
        $usuario->senha    = $dados['senha'];
        $usuario->moedas   = $dados['moedas'];

        return $usuario;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getNome() { return $this->nome; }
    public function getUsername() { return $this->username; }
    public function getEmail() { return $this->email; }
    public function getSenha() { return $this->senha; }
    public function getMoedas() { return $this->moedas; }

    // Setters com validações
    public function setNome($nome) {
        $this->validaNome($nome);
        $this->nome = trim($nome);
    }

    public function setUsername($username) {
        $this->validaUsername($username);
        $this->username = strtolower(trim($username));

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }    

    public function getEmail() {
        return $this->email;
    }

    public function getSenha() {
        return $this->senha;
    }

    public function getMoedas() {
        return $this->moedas;
    }

    // Setters com validações embutidas
    public function setNome($nome) {
        $this->validaNome($nome);
        $this->nome = trim($nome);

    }

    public function setEmail($email) {
        $this->validaEmail($email);
        $this->email = strtolower(trim($email));
    }

    public function setSenha($senha) {
        if (empty($senha) || strlen($senha) < 6) {
            throw new Exception("A senha deve ter no mínimo 6 caracteres");
        }
        $this->senha = password_hash($senha, PASSWORD_DEFAULT);
    }

    public function setMoedas($moedas) {
        $this->moedas = (int) $moedas;
    }

    public function cadastrar() {
        $db = conexao();                
        if ($this->id) {
            $sql = "UPDATE Usuario SET nome = :nome, email = :email, senha = :senha, moedas = :moeda WHERE id_usuario = :id";
            $stmt = $db->prepare($sql);
            return $stmt->execute([
                ':nome'  => $this->nome,
                ':email' => $this->email,
                ':senha' => $this->senha,
                ':moeda' => $this->moedas,
                ':id'    => $this->id
            ]);
        }
        
        $sql = "INSERT INTO Usuario (nome, email, senha, moedas) VALUES (:nome, :email, :senha, :moeda)";
        $stmt = $db->prepare($sql);
        $resultado = $stmt->execute([
            ':nome'  => $this->nome,
            ':email' => $this->email,
            ':senha' => $this->senha,
            ':moeda' => $this->moedas
        ]);

        if ($resultado) {
            $this->id = (int) $db->lastInsertId();
        }
        return $resultado;
    }

    public function load($id) {
        $db = conexao();
        $stmt = $db->prepare("SELECT * FROM Usuario WHERE id_usuario = :id");
        $stmt->execute([':id' => $id]);
        
        if ($dados = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $this->id     = $dados['id_usuario'];
            $this->nome   = $dados['nome']; 
            $this->email  = $dados['email'];
            $this->senha  = $dados['senha']; // Carregado para não perder a senha ao fazer UPDATE
            $this->moedas = $dados['moedas'];
            return true;
        }
        return false;
    }

    public function delete() {
        if (!$this->id) return false;
        $db = conexao();
        $stmt = $db->prepare("DELETE FROM Usuario WHERE id_usuario = :id");
        return $stmt->execute([':id' => $this->id]);
    }
    
    public function validaNome($nome) {
        $nome = trim($nome);

        if (mb_strlen($nome, 'UTF-8') < 3 || mb_strlen($nome, 'UTF-8') > 20) {
            throw new Exception("Nome de usuário deve ter entre 3 e 20 caracteres");
        }

        if (!preg_match('/^[\p{L}\s-]+$/u', $nome)) {
            throw new Exception("O nome de usuário não pode conter caracteres especiais ou números");
        }

        $finalNome = self::getByColumn($nome, 'nome');
        // Ignora a validação se o nome pertencer ao próprio usuário que está sendo alterado
        if (!empty($finalNome) && $finalNome['id_usuario'] != $this->id) {
            throw new Exception('Este nome já está cadastrado');
        }
    }

    public function validaEmail($email) {
        $email = trim($email);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('O email informado é inválido');
        }

        if (strlen($email) > 120) {
            throw new Exception("O email digitado excede o limite de 120 caracteres");
        }

        $finalEmail = self::getByColumn($email, 'email');
        // Ignora a validação se o e-mail pertencer ao próprio usuário que está sendo alterado
        if (!empty($finalEmail) && $finalEmail['id_usuario'] != $this->id) {
            throw new Exception('Este email já está cadastrado');
        }
    }
}
}