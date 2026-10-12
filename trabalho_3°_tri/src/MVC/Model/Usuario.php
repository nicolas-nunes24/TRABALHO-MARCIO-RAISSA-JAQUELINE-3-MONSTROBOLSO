<?php

    require_once "../config.php";

class Usuario{

    private  $id;
    private  $nome;
    private  $email;
    private  $senha;
    private  $moedas;
    private  $db;


    public function __construct(string $nome = null, string $email = null, string $senha = null){

            $this->db = $db;

            if(!isset($nome) && !isset($email) && !isset($senha)) return;
            
            self::validaNome($nome);
            self::validaEmail($email);
            $senha = password_hash($senha, PASSWORD_DEFAULT);

            $this->nome = $nome;
            $this->email = strtolower(trim($email));
            $this->senha = $senha;
            $this->moedas = 0;
            
        }


        //getters
    public function getDb(){
       return $this->db;
    }    

    public function getId(){
        return $this->id;
    }

    public function getNome(){
       return $this->nome;
    }    

    public function getEmail(){
       return $this->email;
    }

    public function getSenha(){
        return $this->senha;
    }

    public function getMoedas(){
       return $this->moedas;
    }


    //setters
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

    public function cadastrar(){
            $db = conexao();                
            if($this->id){
                $sql = "UPDATE Usuario SET nome = :nome, email = :email, senha = :senha, moedas = :moeda WHERE id_usuario = :id";
                $stmt = $db->prepare($sql);
                return $stmt->execute([
                    ':nome' => $this->nome,
                    ':email' => $this->email,
                    ':senha' => $this->senha,
                    ':moeda' => $this->moedas,
                    ':id' => $this->id
                ]);
            }
                $sql = "INSERT INTO Usuario (nome, email, senha, moedas) VALUES (:nome, :email, :senha, :moedas)";
                $stmt = $db->prepare($sql);
                $resultado = $stmt->execute([
                    ':nome' => $nome,
                    ':email' => $email,
                    ':senha' => $senha,
                    ':moeda' => $moedas
                ]);
            if($resultado){
                $this->id = (int) $db->lastInsertId();
            }
            return $resultado;
        }

        public function load($id) {
        $stmt = $this->db->prepare("SELECT * FROM Usuario WHERE id_usuario = :id");
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
        if (!$this->id_usuario) return false;
        $stmt = $this->db->prepare("DELETE FROM Usuario WHERE id_usuario = :id");
        return $stmt->execute([':id' => $this->id_usuario]);
    }
    
    }

    

?>