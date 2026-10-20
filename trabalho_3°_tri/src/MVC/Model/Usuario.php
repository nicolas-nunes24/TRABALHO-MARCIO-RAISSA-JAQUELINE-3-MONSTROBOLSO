<?php

require_once "../config.php";

class Usuario {

    private $id;
    private $nome;
<<<<<<< HEAD
    private $username;
=======
>>>>>>> 6391b5940150286d12520dfccf907e784d15924b
    private $email;
    private $senha;
    private $moedas;

<<<<<<< HEAD
    public function __construct(string $nome = null, string $username = null, string $email = null, string $senha = null) {
        if ($nome === null && $username === null && $email === null && $senha === null) return;
        
        $this->setNome($nome);
        $this->setUsername($username);
=======
    public function __construct(string $nome = null, string $email = null, string $senha = null) {
        if ($nome === null && $email === null && $senha === null) return;
        
        $this->setNome($nome);
>>>>>>> 6391b5940150286d12520dfccf907e784d15924b
        $this->setEmail($email);
        $this->setSenha($senha);
        $this->moedas = 0;
    }

<<<<<<< HEAD
    public static function getByColumn($valor, $coluna) {
        $db = conexao();

        $colunasPermitidas = ['id_usuario', 'nome', 'username', 'email'];
=======
    private static function getByColumn($valor, $coluna) {
        $db = conexao();

        $colunasPermitidas = ['id_usuario', 'nome', 'email'];
>>>>>>> 6391b5940150286d12520dfccf907e784d15924b
        if (!in_array($coluna, $colunasPermitidas)) {
            return false;
        }

        $sql = "SELECT * FROM Usuario WHERE {$coluna} = :valor LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->execute([':valor' => $valor]);
<<<<<<< HEAD
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
=======
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
>>>>>>> 6391b5940150286d12520dfccf907e784d15924b
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

<<<<<<< HEAD
    public function salvar() {
        $db = conexao();                
        if ($this->id) {
            $sql = "UPDATE Usuario 
                    SET nome = :nome, username = :username, email = :email, senha = :senha, moedas = :moeda 
                    WHERE id_usuario = :id";
            $stmt = $db->prepare($sql);
            return $stmt->execute([
                ':nome'     => $this->nome,
                ':username' => $this->username,
                ':email'    => $this->email,
                ':senha'    => $this->senha,
                ':moeda'    => $this->moedas,
                ':id'       => $this->id
            ]);
        }
        
        $sql = "INSERT INTO Usuario (nome, username, email, senha, moedas) 
                VALUES (:nome, :username, :email, :senha, :moeda)";
        $stmt = $db->prepare($sql);
        $resultado = $stmt->execute([
            ':nome'     => $this->nome,
            ':username' => $this->username,
            ':email'    => $this->email,
            ':senha'    => $this->senha,
            ':moeda'    => $this->moedas
=======
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
>>>>>>> 6391b5940150286d12520dfccf907e784d15924b
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
<<<<<<< HEAD
            $this->id       = $dados['id_usuario'];
            $this->nome     = $dados['nome'];
            $this->username = $dados['username'];
            $this->email    = $dados['email'];
            $this->senha    = $dados['senha'];
            $this->moedas   = $dados['moedas'];
=======
            $this->id     = $dados['id_usuario'];
            $this->nome   = $dados['nome']; 
            $this->email  = $dados['email'];
            $this->senha  = $dados['senha']; // Carregado para não perder a senha ao fazer UPDATE
            $this->moedas = $dados['moedas'];
>>>>>>> 6391b5940150286d12520dfccf907e784d15924b
            return true;
        }
        return false;
    }

    public function delete() {
        if (!$this->id) return false;
        $db = conexao();
        $stmt = $db->prepare("DELETE FROM Usuario WHERE id_usuario = :id");
        return $stmt->execute([':id' => $this->id]);
<<<<<<< HEAD
    }

    public function validaNome($nome) {
        $nome = trim($nome);
        if (mb_strlen($nome, 'UTF-8') < 3 || mb_strlen($nome, 'UTF-8') > 50) {
            throw new Exception("O nome deve ter entre 3 e 50 caracteres");
        }
    }

    public function validaUsername($username) {
        $username = trim($username);
        if (mb_strlen($username, 'UTF-8') < 3 || mb_strlen($username, 'UTF-8') > 20) {
            throw new Exception("O nome de usuário (username) deve ter entre 3 e 20 caracteres");
        }
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            throw new Exception("O username só pode conter letras, números e underlines");
        }

        $existente = self::getByColumn($username, 'username');
        if (!empty($existente) && $existente->getId() != $this->id) {
            throw new Exception('Este username já está cadastrado');
        }
    }

    public function validaEmail($email) {
        $email = trim($email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('O e-mail informado é inválido');
        }

        $existente = self::getByColumn($email, 'email');
        if (!empty($existente) && $existente->getId() != $this->id) {
            throw new Exception('Este e-mail já está cadastrado');
=======
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
>>>>>>> 6391b5940150286d12520dfccf907e784d15924b
        }
    }
}