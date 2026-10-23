    <?php

    require_once __DIR__ . "/../../../config/config.php";

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

            $colunasPermitidas = ['id_usuario', 'nome', 'email'];

            if (!in_array($coluna, $colunasPermitidas)) {
                return false;
            }

<<<<<<< HEAD
            $sql = "SELECT * FROM Usuario WHERE {$coluna} = :valor LIMIT 1";
=======
        
        $usuario = new self();
        $usuario->id     = (int) $dados['id_usuario'];
        $usuario->nome   = $dados['nome'];
        $usuario->email  = $dados['email'];
        $usuario->senha  = $dados['senha']; 
        $usuario->moedas = (int) $dados['moedas'];

        return $usuario;
    }

    public function getId() { return $this->id; }
    public function getNome() { return $this->nome; }
    public function getEmail() { return $this->email; }
    public function getSenha() { return $this->senha; }
    public function getMoedas() { return $this->moedas; }

    
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

    public function salvar() {
        return $this->cadastrar();
    }

    public function cadastrar() {
        $db = conexao();                
        if ($this->id) {
            $sql = "UPDATE Usuario SET nome = :nome, email = :email, senha = :senha, moedas = :moeda WHERE id_usuario = :id";
>>>>>>> 79f7883b41261b29acac70dd18f86864678bb739
            $stmt = $db->prepare($sql);
            $stmt->execute([':valor' => $valor]);

            $dados = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$dados) {
                return null;
            }

            // Instancia e hidrata o objeto Usuario
            $usuario = new self();
            $usuario->id     = $dados['id_usuario'];
            $usuario->nome   = $dados['nome'];
            $usuario->email  = $dados['email'];
            $usuario->senha  = $dados['senha'];
            $usuario->moedas = $dados['moedas'];

            return $usuario;
        }

        // Getters
        public function getId() { return $this->id; }
        public function getNome() { return $this->nome; }
        public function getEmail() { return $this->email; }
        public function getSenha() { return $this->senha; }
        public function getMoedas() { return $this->moedas; }

        // Setters com validações
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

        public function salvar() {
            return $this->cadastrar();
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
                $this->senha  = $dados['senha'];
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

<<<<<<< HEAD
            if (!preg_match('/^[\p{L}\s-]+$/u', $nome)) {
                throw new Exception("O nome de usuário não pode conter caracteres especiais ou números");
            }
=======
    public function load($id) {
        $db = conexao();
        $stmt = $db->prepare("SELECT * FROM Usuario WHERE id_usuario = :id");
        $stmt->execute([':id' => $id]);
        
        if ($dados = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $this->id     = (int) $dados['id_usuario'];
            $this->nome   = $dados['nome']; 
            $this->email  = $dados['email'];
            $this->senha  = $dados['senha'];
            $this->moedas = (int) $dados['moedas'];
            return true;
        }
        return false;
    }
>>>>>>> 79f7883b41261b29acac70dd18f86864678bb739

            $usuarioExistente = self::getByColumn($nome, 'nome');
            if (!empty($usuarioExistente) && $usuarioExistente->getId() != $this->id) {
                throw new Exception('Este nome já está cadastrado');
            }
        }

<<<<<<< HEAD
        public function validaEmail($email) {
            $email = trim($email);
=======
        
        if (!preg_match('/^[a-zA-Z0-9_-]+$/u', $nome)) {
            throw new Exception("O nome de usuário pode conter apenas letras, números, hífen e underline");
        }
>>>>>>> 79f7883b41261b29acac70dd18f86864678bb739

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('O email informado é inválido');
            }

            if (strlen($email) > 120) {
                throw new Exception("O email digitado excede o limite de 120 caracteres");
            }

            $usuarioExistente = self::getByColumn($email, 'email');
            if (!empty($usuarioExistente) && $usuarioExistente->getId() != $this->id) {
                throw new Exception('Este email já está cadastrado');
            }
        }
    }