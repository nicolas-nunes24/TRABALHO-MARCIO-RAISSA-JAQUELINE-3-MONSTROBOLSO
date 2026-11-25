<?php
session_start();
if (empty($_SESSION['usuario_id'])) {
    header("Location: src/MVC/View/Usuario/index.php?acao=login");
    exit;
}

require_once __DIR__ . '/classes/autoload.php';

$db = Conexao::obter();
$usuarioId = (int)$_SESSION['usuario_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['trocar_perfil'])) {
        $idPerfil = (int)$_POST['trocar_perfil'];
        $stmt = $db->prepare("SELECT * FROM Perfil WHERE id_perfil = :p AND id_usuario = :u");
        $stmt->execute([':p' => $idPerfil, ':u' => $usuarioId]);
        $p = $stmt->fetch();
        if ($p) {
            $_SESSION['id_perfil'] = (int)$p['id_perfil'];
            $_SESSION['usuario_nome'] = $p['nome_treinador'];
            unset($_SESSION['meu_time']);
            header("Location: site_jogo.php");
            exit;
        }
    }

    if (!empty($_POST['novo_treinador'])) {
        $nome = trim($_POST['novo_treinador']);
        if ($nome !== '') {
            $ins = $db->prepare("INSERT INTO Perfil (id_usuario, nome_treinador, moedas) VALUES (:u, :n, 650)");
            $ins->execute([':u' => $usuarioId, ':n' => $nome]);
            $_SESSION['id_perfil'] = (int)$db->lastInsertId();
            $_SESSION['usuario_nome'] = $nome;
            unset($_SESSION['meu_time']);
            header("Location: site_jogo.php");
            exit;
        }
    }
}

$stmt = $db->prepare("SELECT * FROM Perfil WHERE id_usuario = :u ORDER BY id_perfil ASC");
$stmt->execute([':u' => $usuarioId]);
$perfis = $stmt->fetchAll();
$perfilAtivo = (int)($_SESSION['id_perfil'] ?? ($perfis[0]['id_perfil'] ?? 1));
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perfis - Forçamon</title>
  <link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Nunito:wght@600;700&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Nunito', sans-serif;
      font-weight: 700;
      color: #fff;
      min-height: 100vh;
      background-color: #0a1330;
      background-image: linear-gradient(rgba(6, 11, 28, 0.85), rgba(6, 11, 28, 0.92)), url('imagens_cenarios/bg-pokemon.jpg');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
    }
    
    .topo {
      background: #12285a;
      border-bottom: 4px solid #ffcb05;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 12px 44px;
    }
    .logo {
      display: flex;
      align-items: center;
      font-family: 'Lilita One', sans-serif;
      font-size: 3rem;
      font-style: italic;
      line-height: 1;
      color: #ffcb05;
      -webkit-text-stroke: 6px #3563b8;
      paint-order: stroke fill;
      text-decoration: none;
    }
    .logo svg { width: 46px; height: 46px; margin: 0 3px 0 2px; }
    .btn-topo {
      font-family: 'Lilita One', sans-serif;
      font-size: 1.1rem;
      text-decoration: none;
      border-radius: 9px;
      padding: 10px 24px;
      background: #1d3a8a;
      color: #fff;
      border: 2px solid #fff;
      box-shadow: 0 4px 0 #0a1a4d;
    }
    main { max-width: 960px; margin: 0 auto; padding: 56px 24px; }
    .painel {
      position: relative;
      background: rgba(8, 16, 42, 0.82);
      border: 3px solid #fff;
      border-radius: 16px;
      padding: 44px 26px 28px;
      margin-bottom: 48px;
    }
    .aba {
      position: absolute;
      top: -26px;
      left: 24px;
      background: #12285a;
      border: 3px solid #fff;
      border-bottom: 4px solid #ffcb05;
      border-radius: 12px;
      padding: 4px 24px 2px;
      font-family: 'Lilita One', sans-serif;
      font-size: 1.7rem;
      color: #ffcb05;
    }
    .lista-perfis { display: grid; grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)); gap: 18px; }
    .perfil-card {
      background: #12285a;
      border: 3px solid #fff;
      border-radius: 14px;
      padding: 18px;
      display: flex;
      flex-direction: column;
      gap: 8px;
    }
    .perfil-card.ativo { border-color: #ffcb05; }
    .perfil-nome { font-family: 'Lilita One', sans-serif; font-size: 1.4rem; color: #ffcb05; }
    .perfil-info { font-size: .95rem; color: #dce4ff; }
    .btn-acao {
      margin-top: 8px;
      background: #ffcb05;
      color: #12285a;
      border: none;
      font-family: 'Lilita One', sans-serif;
      font-size: 1.05rem;
      padding: 10px 20px;
      border-radius: 9px;
      box-shadow: 0 4px 0 #c79a00;
      cursor: pointer;
    }
    .btn-acao:active { transform: translateY(3px); box-shadow: 0 1px 0 #c79a00; }
    .form-novo { display: flex; gap: 12px; }
    .form-novo input {
      flex: 1;
      padding: 12px 16px;
      border-radius: 10px;
      border: 3px solid #fff;
      background: #060b1c;
      color: #fff;
      font-family: 'Nunito', sans-serif;
      font-weight: 700;
    }
  </style>
</head>
<body>
  <header class="topo">
    <a class="logo" href="site_jogo.php"><span>FORÇAM</span><svg viewBox="0 0 48 48"><circle cx="24" cy="24" r="21" fill="#3563b8"/><circle cx="24" cy="24" r="17" fill="#fff" stroke="#1a2a55" stroke-width="3"/><path d="M7 24a17 17 0 0 1 34 0z" fill="#e8312f" stroke="#1a2a55" stroke-width="3" stroke-linejoin="round"/><line x1="7" y1="24" x2="41" y2="24" stroke="#1a2a55" stroke-width="3.5"/><circle cx="24" cy="24" r="6" fill="#fff" stroke="#1a2a55" stroke-width="3"/></svg><span>N</span></a>
    <a class="btn-topo" href="site_jogo.php">Menu</a>
  </header>

  <main>
    <section class="painel">
      <div class="aba">Treinadores</div>
      <div class="lista-perfis">
        <?php foreach ($perfis as $p): ?>
          <div class="perfil-card <?= (int)$p['id_perfil'] === $perfilAtivo ? 'ativo' : '' ?>">
            <div class="perfil-nome"><?= htmlspecialchars($p['nome_treinador']) ?></div>
            <div class="perfil-info">Saldo: ₽ <?= number_format((int)$p['moedas'], 0, ',', '.') ?></div>
            <div class="perfil-info">Vitórias: <?= (int)$p['vitorias'] ?> | Derrotas: <?= (int)$p['derrotas'] ?></div>
            <form method="POST">
              <input type="hidden" name="trocar_perfil" value="<?= (int)$p['id_perfil'] ?>">
              <button class="btn-acao" type="submit">
                <?= (int)$p['id_perfil'] === $perfilAtivo ? 'Perfil Atual' : 'Selecionar' ?>
              </button>
            </form>
          </div>
        <?php endforeach; ?>
      </div>
    </section>

    <section class="painel">
      <div class="aba">Novo Perfil</div>
      <form method="POST" class="form-novo">
        <input type="text" name="novo_treinador" placeholder="Nome do treinador..." maxlength="40" required>
        <button class="btn-acao" type="submit">Criar</button>
      </form>
    </section>
  </main>
</body>
</html>