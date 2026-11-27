<?php

require_once __DIR__ . "/../../../config/config.php";

class Usuario {

    private $id;
    private $nome;
    private $email;
    private $senha;
    private $moedas;

    /**
     * Recebe opcionalmente o nome ($nome), o e-mail ($email) e a senha ($senha).
     * Caso os três parâmetros sejam informados, valida e define os dados do usuário usando os setters e inicializa o saldo de moedas como 0.
     */
    public function __construct(string $nome = null, string $email = null, string $senha = null) {
        if ($nome !== null && $email !== null && $senha !== null) {
            $this->setNome($nome);
            $this->setEmail($email);
            $this->setSenha($senha);
            $this->moedas = 0;
        }
    }

    /**
     * Recebe o valor a ser buscado ($valor) e o nome da coluna ($coluna).
     * Valida se a coluna é permitida, consulta o primeiro usuário correspondente no banco de dados e retorna uma instância de Usuario preenchida (ou null se não encontrar, ou false se a coluna for inválida).
     */
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

    /**
     * Retorna o ID do usuário.
     */
    public function getId() { return $this->id; }

    /**
     * Retorna o nome do usuário.
     */
    public function getNome() { return $this->nome; }

    /**
     * Retorna o e-mail do usuário.
     */
    public function getEmail() { return $this->email; }

    /**
     * Retorna o hash da senha do usuário.
     */
    public function getSenha() { return $this->senha; }

    /**
     * Retorna a quantidade de moedas do usuário.
     */
    public function getMoedas() { return $this->moedas; }

    /**
     * Recebe o nome do usuário ($nome).
     * Remove espaços extras, valida se o tamanho está entre 3 e 20 caracteres e se o nome já não está em uso por outro usuário (lançando exceção em caso de erro) antes de atribuí-lo.
     */
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

    /**
     * Recebe o e-mail do usuário ($email).
     * Normaliza para minúsculas sem espaços, valida o formato do e-mail e verifica se já não está cadastrado por outro usuário (lançando exceção em caso de erro) antes de atribuí-lo.
     */
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

    /**
     * Recebe a senha em texto puro ($senha).
     * Valida se possui pelo menos 6 caracteres (lançando exceção caso contrário) e armazena o hash criptografado da senha.
     */
    public function setSenha($senha) {
        if (strlen($senha) < 6) {
            throw new Exception("A senha deve ter pelo menos 6 caracteres.");
        }
        $this->senha = password_hash($senha, PASSWORD_DEFAULT);
    }

    /**
     * Recebe a quantidade de moedas ($moedas).
     * Converte o valor para inteiro e define o saldo de moedas do usuário.
     */
    public function setMoedas($moedas) {
        $this->moedas = (int) $moedas;
    }

    /**
     * Atualiza o registro do usuário no banco caso já possua ID, ou insere um novo registro definindo o ID gerado, retornando um booleano indicando o sucesso da operação.
     */
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

    /**
     * Remove o usuário do banco de dados caso possua ID e retorna um booleano indicando se a exclusão foi realizada.
     */
    public function delete() {
        if (!$this->id) return false;
        $db = conexao();
        $stmt = $db->prepare("DELETE FROM Usuario WHERE id_usuario = :id");
        return $stmt->execute([':id' => $this->id]);
    }
}