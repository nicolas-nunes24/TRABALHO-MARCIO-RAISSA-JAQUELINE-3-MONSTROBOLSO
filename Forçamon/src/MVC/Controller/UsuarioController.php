<?php

require_once __DIR__ . "/../Model/Usuario.php";
require_once __DIR__ . "/../../../config/config.php";
require_once __DIR__ . "/../../../log/registrar_log.php";

class UsuarioController {

    /**
     * Inicia a sessão PHP caso ela ainda não esteja ativa.
     */
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Recebe o nome da view ($view) e opcionalmente um array de dados ($dados, padrão vazio).
     * Extrai os elementos do array como variáveis locais e inclui o arquivo da view correspondente.
     */
    private function renderizar($view, $dados = []) {
        extract($dados);
        require_once __DIR__ . "/../View/Usuario/$view.php";
    }

    /**
     * Renderiza a tela de cadastro de usuário.
     */
    public function cadastro() {
        $this->renderizar('cadastro');
    }

    /**
     * Renderiza a tela de login.
     */
    public function login() {
        $this->renderizar('login');
    }

    /**
     * Renderiza a tela de recuperação de senha.
     */
    public function recuperar() {
        $this->renderizar('recuperar');
    }

    /**
     * Valida a requisição POST e o e-mail informado, verifica se o usuário existe, registra o log da solicitação, define uma mensagem de sucesso na sessão e redireciona para a tela de recuperação (ou redireciona com erro em caso de falha).
     */
    public function enviarRecuperacao() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            registrarLog('login', "Tentativa de recuperação de senha falhou: requisição inválida.", 'Desconhecido');
            $this->redirecionarComErro('recuperar', 'Requisição inválida.');
        }

        $email = strtolower(trim($_POST['email'] ?? ''));

        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            registrarLog('login', "Tentativa de recuperação com e-mail inválido: {$email}", 'Desconhecido');
            $this->redirecionarComErro('recuperar', 'Informe um e-mail válido.');
        }

        try {
            $user = Usuario::getByColumn($email, 'email');

            if ($user) {
                registrarLog('login', "Solicitação de recuperação de senha enviada para: {$email}", $user->getNome());
            } else {
                registrarLog('login', "Solicitação de recuperação para e-mail não cadastrado: {$email}", 'Desconhecido');
            }

            $_SESSION['flash_success'] = 'Se o e-mail estiver cadastrado, enviamos as instruções para redefinir sua senha!';
            header("Location: ?acao=recuperar");
            exit;
        } catch (Exception $e) {
            registrarLog('login', "Erro técnico na recuperação de senha ({$email}): " . $e->getMessage(), 'Desconhecido');
            $this->redirecionarComErro('recuperar', 'Erro ao processar solicitação: ' . $e->getMessage());
        }
    }

    /**
     * Verifica se o usuário está autenticado e existe no banco, garante um perfil ativo na sessão, consulta o saldo de moedas do perfil e renderiza a view da loja passando esse saldo.
     */
    public function dashboard() {
        if (empty($_SESSION['usuario_id'])) {
            header("Location: ?acao=login");
            exit;
        }

        $usuario = Usuario::getByColumn($_SESSION['usuario_id'], 'id_usuario');
        if (!$usuario) {
            $this->logout();
        }

        $this->garantirPerfilAtivo((int)$usuario->getId(), $usuario->getNome());

        $db = conexao();
        $st = $db->prepare("SELECT moedas FROM Perfil WHERE id_perfil = :p");
        $st->execute([':p' => $_SESSION['id_perfil']]);
        $moedasPerfil = (int)$st->fetchColumn();

        $this->renderizar('../Loja/lojinha', ['moeda' => $moedasPerfil]);
    }

    /**
     * Valida a requisição POST e os campos obrigatórios, cria e salva um novo usuário junto com um perfil inicial de 650 moedas, registra o log, define a mensagem de sucesso e redireciona para o login (ou redireciona com erro em caso de falha).
     */
    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            registrarLog('registro', "Tentativa de cadastro falhou: requisição inválida.", 'Desconhecido');
            $this->redirecionarComErro('cadastro', 'Requisição inválida.');
        }

        $nome  = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';

        if (!$nome || !$email || !$senha) {
            registrarLog('registro', "Tentativa de cadastro falhou: campos obrigatórios em branco (E-mail: {$email}).", $nome ?: 'Desconhecido');
            $this->redirecionarComErro('cadastro', 'Preencha todos os campos.');
        }

        try {
            $usuario = new Usuario($nome, $email, $senha);

            if ($usuario->salvar()) {
                $db = conexao();
                $st = $db->prepare("INSERT INTO Perfil (id_usuario, nome_treinador, moedas) VALUES (:u, :n, 650)");
                $st->execute([':u' => $usuario->getId(), ':n' => $nome]);

                $_SESSION['flash_success'] = 'Conta criada com sucesso!';
                registrarLog('registro', "Novo usuário cadastrado: {$email}", $nome);

                header("Location: ?acao=login");
                exit;
            }

            registrarLog('registro', "Tentativa de cadastro falhou para {$email}: erro ao salvar os dados.", $nome);
            $this->redirecionarComErro('cadastro', 'Erro ao salvar os dados.');
        } catch (Exception $e) {
            registrarLog('registro', "Tentativa de cadastro falhou para {$email}: " . $e->getMessage(), $nome);
            $this->redirecionarComErro('cadastro', $e->getMessage());
        }
    }

    /**
     * Valida a requisição POST e as credenciais enviadas, busca o usuário por e-mail ou nome, verifica a senha, regenera a sessão, ativa o perfil do usuário, registra o log e redireciona para o jogo (ou redireciona com erro em caso de falha).
     */
    public function fazerLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            registrarLog('login', "Tentativa de login falhou: requisição inválida.", 'Desconhecido');
            $this->redirecionarComErro('login', 'Requisição inválida.');
        }

        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';

        if (!$email || !$senha) {
            registrarLog('login', "Tentativa de login falhou: campos em branco.", 'Desconhecido');
            $this->redirecionarComErro('login', 'Informe seu e-mail e senha.');
        }

        try {
            $user = Usuario::getByColumn(strtolower($email), 'email');
            if (!$user) {
                $user = Usuario::getByColumn($email, 'nome');
            }

            if ($user && password_verify($senha, $user->getSenha())) {
                session_regenerate_id(true);

                $_SESSION['usuario_id'] = $user->getId();
                $this->garantirPerfilAtivo((int)$user->getId(), $user->getNome());

                registrarLog('login', "Login realizado com sucesso.", $user->getNome());

                header("Location: ../../../../site_jogo.php");
                exit;
            }

            registrarLog('login', "Tentativa de login falhou para: {$email}", 'Desconhecido');
            $this->redirecionarComErro('login', 'E-mail ou senha incorretos.');
        } catch (Exception $e) {
            registrarLog('login', "Erro técnico no login para {$email}: " . $e->getMessage(), 'Desconhecido');
            $this->redirecionarComErro('login', 'Erro no login: ' . $e->getMessage());
        }
    }

    /**
     * Recebe o ID do usuário ($usuarioId) e o nome padrão do treinador ($nomePadrao).
     * Verifica se o perfil atual da sessão pertence ao usuário; caso não exista ou seja inválido, busca o primeiro perfil cadastrado para o usuário ou cria um novo com 650 moedas, atualizando os dados do perfil na sessão.
     */
    private function garantirPerfilAtivo(int $usuarioId, string $nomePadrao): void {
        $db = conexao();
        if (!empty($_SESSION['id_perfil'])) {
            $chk = $db->prepare("SELECT id_perfil, nome_treinador FROM Perfil WHERE id_perfil = :p AND id_usuario = :u");
            $chk->execute([':p' => $_SESSION['id_perfil'], ':u' => $usuarioId]);
            $atual = $chk->fetch(PDO::FETCH_ASSOC);
            if ($atual) {
                $_SESSION['usuario_nome'] = $atual['nome_treinador'];
                return;
            }
        }

        $st = $db->prepare("SELECT id_perfil, nome_treinador FROM Perfil WHERE id_usuario = :u ORDER BY id_perfil ASC LIMIT 1");
        $st->execute([':u' => $usuarioId]);
        $perfil = $st->fetch(PDO::FETCH_ASSOC);

        if (!$perfil) {
            $ins = $db->prepare("INSERT INTO Perfil (id_usuario, nome_treinador, moedas) VALUES (:u, :n, 650)");
            $ins->execute([':u' => $usuarioId, ':n' => $nomePadrao]);
            $_SESSION['id_perfil'] = (int)$db->lastInsertId();
            $_SESSION['usuario_nome'] = $nomePadrao;
        } else {
            $_SESSION['id_perfil'] = (int)$perfil['id_perfil'];
            $_SESSION['usuario_nome'] = $perfil['nome_treinador'];
        }
    }

    /**
     * Limpa os dados da sessão, remove o cookie de sessão, destrói a sessão atual e redireciona para a tela de login.
     */
    public function logout() {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        header("Location: ?acao=login");
        exit;
    }

    /**
     * Recebe a ação de destino ($acao) e a mensagem de erro ($mensagem).
     * Armazena a mensagem de erro na sessão, redireciona para a ação informada e encerra a execução do script.
     */
    private function redirecionarComErro($acao, $mensagem) {
        $_SESSION['flash_error'] = $mensagem;
        header("Location: ?acao=$acao");
        exit;
    }
}