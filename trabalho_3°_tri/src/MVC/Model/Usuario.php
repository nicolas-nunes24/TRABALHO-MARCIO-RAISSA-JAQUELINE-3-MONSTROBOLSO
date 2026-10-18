<?php

require_once "../config.php";

class Usuario {

    private $id;
    private $nome;
    private $email;
    private $senha;
    private $moedas;
    private $db;

    public function __construct(string $nome = null, string $email = null, string $senha = null) {
        if (!isset($nome) && !isset($email) && !isset($senha)) return;
        
        self::validaNome($nome);
        self::validaEmail($email);
        $senha = password_hash($senha, PASSWORD_DEFAULT);

        $this->nome = $nome;
        $this->email = strtolower(trim($email));
        $this->senha = $senha;
        $this->moedas = 0;
    }

   
    private static function getByColumn($valor, $coluna) {
        $db = conexao();

        $colunasPermitidas = ['id_usuario', 'nome', 'email'];
        if (!in_array($coluna, $colunasPermitidas)) {
            return false;
        }

        $sql = "SELECT * FROM Usuario WHERE {$coluna} = :valor LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->execute([':valor' => $valor]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Getters
    public function getDb() {
        return $this->db;
    }    

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

    // Setters
    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setSenha($senha) {
        $this->senha = password_hash($senha, PASSWORD_DEFAULT);
    }

    public function setMoedas($moedas) {
        $this->moedas = $moedas;
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
        if (!empty($finalNome)) {
            throw new Exception('Este nome já está cadastrado');
        }
    }

    private static function validaEmail($email) {
        $email = trim($email);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('O email informado é inválido');
        }

        if (strlen($email) > 120) {
            throw new Exception("O email digitado excede o limite de 120 caracteres");
        }

        $finalEmail = self::getByColumn($email, 'email');
        if (!empty($finalEmail)) {
            throw new Exception('Este email já está cadastrado');
        }
    }
}
?>

```