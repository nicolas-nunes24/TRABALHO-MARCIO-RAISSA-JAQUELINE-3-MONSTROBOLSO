<?php

require_once __DIR__ . "/../../../config/config.php";

class Usuario {

    private $id;
    private $nome;
    private $email;
    private $senha;
    private $moedas;

    public function __construct(string $nome = null, string $email = null, string $senha = null) {
        if ($nome !== null && $email !== null && $senha !== null) {
            $this->setNome($nome);
            $this->setEmail($email);
            $this->setSenha($senha);
            $this->moedas = 0;
        }
    }

    public static function getByColumn($valor, $coluna) {
        $db = conexao();
        $colunasPermitidas = ['id_usuario', 'nome', 'email'];

        if (!in_array($coluna, $colunasPermitidas)) {
            return false;
        }

        $stmt = $db->prepare("SELECT * FROM Usuario WHERE {$coluna} = :valor LIMIT 1");
        $stmt->execute([':valor' => $valor]);

        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$dados) return null;

        $usuario = new self();
        $usuario->id     = $dados['id_usuario'];
        $usuario->nome   = $dados['nome'];
        $usuario->email  = $dados['email'];
        $usuario->senha  = $dados['senha'];
        $usuario->moedas = $dados['moedas'];

        return $usuario;
    }


    public function getId() { return $this->id; }
    public function getNome() { return $this->nome; }
    public function getEmail() { return $this->email; }
    public function getSenha() { return $this->senha; }
    public function getMoedas() { return $this->moedas; }

 
    public function setNome($nome) {
        $nome = trim($nome);
        if (mb_strlen($nome) < 3 || mb_strlen($nome) > 20) {
            throw new Exception("O nome deve ter entre 3 e 20 caracteres.");
        }

        $existente = self::getByColumn($nome, 'nome');
        if ($existente && $existente->getId() !== $this->id) {
            throw new Exception("Este nome já está em uso.");
        }

        $this->nome = $nome;
    }

    public function setEmail($email) {
        $email = strtolower(trim($email));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Informe um e-mail válido.");
        }

        $existente = self::getByColumn($email, 'email');
        if ($existente && $existente->getId() !== $this->id) {
            throw new Exception("Este e-mail já está cadastrado.");
        }

        $this->email = $email;
    }

    public function setSenha($senha) {
        if (strlen($senha) < 6) {
            throw new Exception("A senha deve ter pelo menos 6 caracteres.");
        }
        $this->senha = password_hash($senha, PASSWORD_DEFAULT);
    }

    public function setMoedas($moedas) {
        $this->moedas = (int) $moedas;
    }

    public function salvar() {
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
        $ok = $stmt->execute([
            ':nome'  => $this->nome,
            ':email' => $this->email,
            ':senha' => $this->senha,
            ':moeda' => $this->moedas
        ]);

        if ($ok) {
            $this->id = (int) $db->lastInsertId();
        }

        return $ok;
    }

    public function delete() {
        if (!$this->id) return false;
        $db = conexao();
        $stmt = $db->prepare("DELETE FROM Usuario WHERE id_usuario = :id");
        return $stmt->execute([':id' => $this->id]);
    }
}