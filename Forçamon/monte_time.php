<?php
  require_once __DIR__ . "/log/registrar_log.php";
  require_once __DIR__ . "/classes/autoload.php";
  session_start();

  // forçamons que só aparecem evoluindo outro (não entram no time)
  $soPorEvolucao = CatalogoForcamon::nomesSoPorEvolucao();
  $semBloqueados = fn(array $time) => array_values(array_filter(
      $time,
      fn($p) => !in_array($p['nome'] ?? '', $soPorEvolucao, true)
  ));
  
  // salva o time escolhido e manda pra batalha
  if (isset($_POST['time_selecionado'])) {
      $time = $semBloqueados(json_decode($_POST['time_selecionado'], true) ?: []);
      if (count($time) !== 3) {
          header("Location: monte_time.php");
          exit;
      }
      $_SESSION['meu_time'] = $time;
      $nomes = implode(', ', array_column($time, 'nome'));
      registrarLog('time', "Montou o time: [{$nomes}].");
      header("Location: jogo.php");
      exit;
  }

  $meuTimeSalvo = $semBloqueados($_SESSION['meu_time'] ?? []);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Time - Forçamon</title>
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
    /* topo */
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
      filter: drop-shadow(0 3px 0 rgba(0,0,0,.35));
    }
    .logo svg { width: 46px; height: 46px; margin: 0 3px 0 2px; -webkit-text-stroke: 0; }
    .topo-btns { display: flex; align-items: center; gap: 14px; }
    .btn-topo {
      font-family: 'Lilita One', sans-serif;
      font-size: 1.1rem;
      text-decoration: none;
      border: none;
      border-radius: 9px;
      padding: 10px 24px;
      cursor: pointer;
    }
    .btn-topo:active { transform: translateY(3px); }
    .btn-menu { background: #1d3a8a; color: #fff; border: 2px solid #fff; box-shadow: 0 4px 0 #0a1a4d; }
    .btn-menu:active { box-shadow: 0 1px 0 #0a1a4d; }

    main { max-width: 1100px; margin: 0 auto; padding: 56px 24px 60px; }

    /* painéis com a abinha do título */
    .painel {
      position: relative;
      background: rgba(8, 16, 42, 0.78);
      border: 3px solid #fff;
      border-radius: 16px;
      padding: 44px 26px 28px;
      margin-bottom: 56px;
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
      font-size: 1.8rem;
      color: #ffcb05;
      text-shadow: 0 3px 0 rgba(0,0,0,.5);
      letter-spacing: .5px;
    }
    .contador {
      position: absolute;
      top: -20px;
      right: 24px;
      background: #12285a;
      border: 3px solid #fff;
      border-radius: 10px;
      padding: 3px 16px 1px;
      font-family: 'Lilita One', sans-serif;
      font-size: 1.3rem;
      color: #ffcb05;
    }
    .contador.cheio { color: #7CFC7C; border-color: #7CFC7C; }

    /* slots */
    .slots { display: flex; justify-content: center; gap: 22px; flex-wrap: wrap; }
    .slot {
      position: relative;
      width: 170px;
      height: 220px;
      border: 3px dashed rgba(255,255,255,.4);
      border-radius: 14px;
      background: rgba(18, 40, 90, 0.6);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 12px;
      cursor: pointer;
      overflow: hidden;
      color: rgba(255,255,255,.5);
      font-family: 'Lilita One', sans-serif;
      font-size: 1.1rem;
    }
    .slot.destaque { border-color: #ffcb05; border-style: solid; }
    .slot.preenchido { border: 3px solid #fff; background: #eef1f6; justify-content: flex-start; gap: 0; color: #fff; }
    .slot.preenchido:hover { border-color: #c9302c; }
    .slot.preenchido img { width: 100%; flex: 1; min-height: 0; object-fit: contain; padding: 10px; }
    .slot .rotulo {
      width: 100%;
      background: #12285a;
      border-top: 3px solid #ffcb05;
      padding: 6px;
      text-align: center;
    }
    .selo {
      position: absolute;
      top: 6px;
      left: 6px;
      width: 28px;
      height: 28px;
      border-radius: 50%;
      background: #ffcb05;
      border: 2px solid #12285a;
      color: #12285a;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1rem;
    }

    /* pokébola apagada dos slots vazios */
    .bolinha {
      position: relative;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      border: 3px solid #fff;
      background: transparent;
      opacity: .3;
      overflow: hidden;
    }
    .bolinha::before { content: ''; position: absolute; left: 0; right: 0; top: 50%; height: 3px; margin-top: -1.5px; background: #fff; }
    .bolinha::after { content: ''; position: absolute; left: 50%; top: 50%; width: 14px; height: 14px; margin: -7px 0 0 -7px; border-radius: 50%; border: 3px solid #fff; background: #12285a; }

    .acoes { display: flex; flex-direction: column; align-items: center; margin-top: 26px; gap: 10px; }
    .botao {
      background: #ffcb05;
      color: #12285a;
      border: none;
      font-family: 'Lilita One', sans-serif;
      font-size: 1.25rem;
      padding: 12px 38px;
      border-radius: 10px;
      box-shadow: 0 5px 0 #c79a00;
      cursor: pointer;
    }
    .botao:active { transform: translateY(4px); box-shadow: 0 1px 0 #c79a00; }
    .botao.travado { filter: grayscale(.8) brightness(.75); }
    .aviso { min-height: 20px; color: #ffb4b0; }

    /* forçamons */
    .grade-cards { display: grid; grid-template-columns: repeat(auto-fill, minmax(170px, 1fr)); gap: 18px; }
    .card {
      background: #eef1f6;
      border: 3px solid #fff;
      border-radius: 14px;
      overflow: hidden;
      cursor: grab;
      display: flex;
      flex-direction: column;
    }
    .card:hover { border-color: #ffcb05; }
    .card img { width: 100%; aspect-ratio: 1/1; object-fit: contain; padding: 12px; pointer-events: none; }
    .card .info {
      background: #12285a;
      border-top: 3px solid #ffcb05;
      padding: 8px 10px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 6px;
    }
    .card .nome { font-family: 'Lilita One', sans-serif; font-size: 1.05rem; }

    .tipo { font-family: 'Lilita One', sans-serif; font-size: .78rem; padding: 2px 9px; border-radius: 999px; border: 2px solid rgba(255,255,255,.85); }
    .t-fogo { background: #e8590c; }
    .t-planta { background: #3a9d23; }
    .t-normal { background: #8a8d94; }
    .t-psiquico { background: #d6336c; }
    .t-dark { background: #4a3b34; }
    .t-agua { background: #1c7ed6; }
    .t-dragao { background: #5b3fd1; }
    .t-aco { background: #5c7a94; }

    .card.bloqueado { cursor: not-allowed; opacity: .55; filter: grayscale(.7); }
    .card.bloqueado:hover { border-color: #fff; }
    .evo-nota { padding: 4px 10px; font-size: .75rem; line-height: 1.25; text-align: center; color: #12285a; background: #ffcb05; }

    /* já escolhido */
    .card.sumido { opacity: .3; filter: grayscale(1); pointer-events: none; }

    @media (max-width: 760px) {
      .topo { padding: 10px 16px; }
      .logo { font-size: 2rem; -webkit-text-stroke-width: 4px; }
      .logo svg { width: 30px; height: 30px; }
      .btn-topo { padding: 8px 14px; font-size: .95rem; }
      .slot { width: 120px; height: 170px; }
    }
  </style>
</head>
<body>

  <header class="topo">
    <a class="logo" href="site_jogo.php"><span>FORÇAM</span><svg viewBox="0 0 48 48"><circle cx="24" cy="24" r="21" fill="#3563b8"/><circle cx="24" cy="24" r="17" fill="#fff" stroke="#1a2a55" stroke-width="3"/><path d="M7 24a17 17 0 0 1 34 0z" fill="#e8312f" stroke="#1a2a55" stroke-width="3" stroke-linejoin="round"/><line x1="7" y1="24" x2="41" y2="24" stroke="#1a2a55" stroke-width="3.5"/><circle cx="24" cy="24" r="6" fill="#fff" stroke="#1a2a55" stroke-width="3"/></svg><span>N</span></a>
    <div class="topo-btns">
      <a class="btn-topo btn-menu" href="site_jogo.php">Menu</a>
    </div>
  </header>

  <main>
    <section class="painel">
      <div class="aba">Seu time</div>
      <div class="contador" id="contador">0/3</div>

      <div class="slots">
        <div class="slot" id="s1" data-num="1" ondragover="segurar(event)" ondragleave="tirarDestaque(event)" ondrop="soltar(event)" onclick="limparSlot(this)"><span class="bolinha"></span>Slot 1</div>
        <div class="slot" id="s2" data-num="2" ondragover="segurar(event)" ondragleave="tirarDestaque(event)" ondrop="soltar(event)" onclick="limparSlot(this)"><span class="bolinha"></span>Slot 2</div>
        <div class="slot" id="s3" data-num="3" ondragover="segurar(event)" ondragleave="tirarDestaque(event)" ondrop="soltar(event)" onclick="limparSlot(this)"><span class="bolinha"></span>Slot 3</div>
      </div>

      <form id="meuForm" method="POST" action="" class="acoes">
        <input type="hidden" name="time_selecionado" id="inputEscondido">
        <button type="button" class="botao travado" id="botaoBatalha" onclick="enviarTime()">Bora pra batalha!</button>
        <div class="aviso" id="aviso"></div>
      </form>
    </section>

    <section class="painel">
      <div class="aba">Forçamons</div>

      <div class="grade-cards">
        <div class="card bloqueado" id="rubizard" data-img="imagens-personagens/rubizard.png" data-nome="Rubizard" data-tipo="fogo"><img src="imagens-personagens/rubizard.png"><div class="info"><span class="nome">Rubizard</span><span class="tipo t-fogo">Fogo</span></div><div class="evo-nota">Disponível somente para evolução</div></div>
        <div class="card" id="nilldrill" draggable="true" ondragstart="pegarCard(event)" onclick="clicarCard(this)" data-img="imagens-personagens/nilldrill.png" data-nome="Nilldrill" data-tipo="planta"><img src="imagens-personagens/nilldrill.png"><div class="info"><span class="nome">Nilldrill</span><span class="tipo t-planta">Planta</span></div></div>
        <div class="card" id="vianalax" draggable="true" ondragstart="pegarCard(event)" onclick="clicarCard(this)" data-img="imagens-personagens/vianalax.png" data-nome="Vianalax" data-tipo="normal"><img src="imagens-personagens/vianalax.png"><div class="info"><span class="nome">Vianalax</span><span class="tipo t-normal">Normal</span></div></div>
        <div class="card" id="violetwo" draggable="true" ondragstart="pegarCard(event)" onclick="clicarCard(this)" data-img="imagens-personagens/violetwo.png" data-nome="Violetwo" data-tipo="psiquico"><img src="imagens-personagens/violetwo.png"><div class="info"><span class="nome">Violetwo</span><span class="tipo t-psiquico">Psíquico</span></div></div>
        <div class="card" id="darkvi" draggable="true" ondragstart="pegarCard(event)" onclick="clicarCard(this)" data-img="imagens-personagens/darkvi.png" data-nome="Darkvi" data-tipo="dark"><img src="imagens-personagens/darkvi.png"><div class="info"><span class="nome">Darkvi</span><span class="tipo t-dark">Sombrio</span></div></div>
        <div class="card" id="brrpatana" draggable="true" ondragstart="pegarCard(event)" onclick="clicarCard(this)" data-img="imagens-personagens/brrpatana.png" data-nome="Brrpatana" data-tipo="agua"><img src="imagens-personagens/brrpatana.png"><div class="info"><span class="nome">Brrpatana</span><span class="tipo t-agua">Água</span></div></div>
        <div class="card" id="meloetta" draggable="true" ondragstart="pegarCard(event)" onclick="clicarCard(this)" data-img="imagens-personagens/meloetta.png" data-nome="Meloetta" data-tipo="normal"><img src="imagens-personagens/meloetta.png"><div class="info"><span class="nome">Meloetta</span><span class="tipo t-normal">Normal</span></div></div>
        <div class="card" id="dragofav" draggable="true" ondragstart="pegarCard(event)" onclick="clicarCard(this)" data-img="imagens-personagens/dragofav.png" data-nome="Dragofav" data-tipo="dragao"><img src="imagens-personagens/dragofav.png"><div class="info"><span class="nome">Dragofav</span><span class="tipo t-dragao">Dragão</span></div><div class="evo-nota">Evolui para Rubizard (Pedra Preciosa)</div></div>
        <div class="card" id="velostervroom" draggable="true" ondragstart="pegarCard(event)" onclick="clicarCard(this)" data-img="imagens-personagens/velostervroom.png" data-nome="Velostervroom" data-tipo="aco"><img src="imagens-personagens/velostervroom.png"><div class="info"><span class="nome">Velostervroom</span><span class="tipo t-aco">Aço</span></div></div>
      </div>
    </section>
  </main>

  <script>
    let cardSendoArrastado = "";
    let timeVelho = <?php echo json_encode($meuTimeSalvo); ?>;

    // recoloca o time salvo nos slots
    window.onload = function() {
      if (timeVelho && timeVelho.length > 0) {
        timeVelho.forEach((bicho, i) => {
          let slotzinho = document.getElementById(`s${i + 1}`);
          let cardzinho = document.getElementById(bicho.nome.toLowerCase());
          if (slotzinho && cardzinho) colocarNoSlot(slotzinho, cardzinho.id);
        });
      }
      atualizarContador();
    };

    function pegarCard(e) {
      cardSendoArrastado = e.currentTarget.id;
      e.dataTransfer.setData("text/plain", cardSendoArrastado);
    }

    function segurar(e) {
      e.preventDefault();
      e.currentTarget.classList.add('destaque');
    }

    function tirarDestaque(e) {
      e.currentTarget.classList.remove('destaque');
    }

    function soltar(e) {
      e.preventDefault();
      let slot = e.currentTarget;
      slot.classList.remove('destaque');
      if (cardSendoArrastado) {
        if (slot.classList.contains('preenchido')) limparSlot(slot);
        colocarNoSlot(slot, cardSendoArrastado);
        cardSendoArrastado = "";
      }
    }

    function clicarCard(cardEl) {
      let slots = document.querySelectorAll('.slot');
      for (let s of slots) {
        if (!s.classList.contains('preenchido')) {
          colocarNoSlot(s, cardEl.id);
          return;
        }
      }
      document.getElementById('aviso').textContent = "Time cheio. Clique num slot pra tirar um monstro.";
    }

    function colocarNoSlot(slot, cardId) {
      let cardEl = document.getElementById(cardId);
      if (!cardEl || cardEl.classList.contains('bloqueado')) return;
      slot.classList.add('preenchido');
      slot.setAttribute('data-card-id', cardId);
      slot.setAttribute('data-nome', cardEl.getAttribute('data-nome'));
      slot.setAttribute('data-img', cardEl.getAttribute('data-img'));
      slot.setAttribute('data-tipo', cardEl.getAttribute('data-tipo'));
      slot.innerHTML = `<img src="${cardEl.getAttribute('data-img')}" alt="bicho"><div class="rotulo">${cardEl.getAttribute('data-nome')}</div><span class="selo">${slot.getAttribute('data-num')}</span>`;
      cardEl.classList.add('sumido');
      atualizarContador();
    }

    function limparSlot(slot) {
      if (slot.classList.contains('preenchido')) {
        let cardId = slot.getAttribute('data-card-id');
        if (cardId) {
          let cardEl = document.getElementById(cardId);
          if (cardEl) cardEl.classList.remove('sumido');
        }
        let num = slot.getAttribute('data-num');
        slot.className = 'slot';
        slot.removeAttribute('data-card-id');
        slot.removeAttribute('data-nome');
        slot.removeAttribute('data-img');
        slot.removeAttribute('data-tipo');
        slot.innerHTML = `<span class="bolinha"></span>Slot ${num}`;
        atualizarContador();
      }
    }

    function atualizarContador() {
      let qtd = document.querySelectorAll('.slot.preenchido').length;
      let contador = document.getElementById('contador');
      contador.textContent = `${qtd}/3`;
      contador.classList.toggle('cheio', qtd === 3);
      document.getElementById('botaoBatalha').classList.toggle('travado', qtd < 3);
      if (qtd === 3) document.getElementById('aviso').textContent = "";
    }

    function enviarTime() {
      let preenchidos = document.querySelectorAll('.slot.preenchido');
      if (preenchidos.length < 3) {
        document.getElementById('aviso').textContent = "Escolha 3 monstros antes de ir batalhar.";
        return;
      }
      let meuTimeFinal = [];
      preenchidos.forEach(s => {
        meuTimeFinal.push({
          nome: s.getAttribute('data-nome'),
          img: s.getAttribute('data-img'),
          tipo: s.getAttribute('data-tipo'),
          hp: 100
        });
      });
      document.getElementById('inputEscondido').value = JSON.stringify(meuTimeFinal);
      document.getElementById('meuForm').submit();
    }
  </script>
</body>
</html>