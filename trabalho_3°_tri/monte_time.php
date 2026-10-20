<?php
  // Começa a sessão pra guardar o time que o mano escolher
  session_start();

  // Se o cara confirmou o time, joga pra sessão e manda pra batalha
  if (isset($_POST['time_selecionado'])) {
      $_SESSION['meu_time'] = json_decode($_POST['time_selecionado'], true);
      header("Location: jogo.php");
      exit;
  }

  // Pega o time que já tava salvo antes (caso o mano volte da batalha)
  $meuTimeSalvo = isset($_SESSION['meu_time']) ?$_SESSION['meu_time'] : [];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Montar Time - MonstroBolso</title>
  <style>
    /* Estilo básico */
    * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Courier New', Courier, monospace; }
    
    body { 
      background-color: #121417; 
      /* Fundo escurecido com a imagem bg-pokemon.jpg */
      background-image: linear-gradient(rgba(18, 20, 23, 0.88), rgba(18, 20, 23, 0.88)), url('bg-pokemon.jpg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-attachment: fixed;
      color: #fff; 
      display: flex; 
      flex-direction: column; 
      align-items: center; 
      padding: 20px; 
      min-height: 100vh; 
    }
    
    header { width: 100%; max-width: 680px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 2px solid #334155; padding-bottom: 10px; }
    h1 { font-size: 1.4rem; text-transform: uppercase; }
    .voltar { background: #1e293b; color: #94a3b8; border: 1px solid #334155; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 0.85rem; }

    .caixa { 
      width: 100%; 
      max-width: 680px; 
      background-color: rgba(30, 41, 59, 0.95); 
      border: 2px solid #334155; 
      border-radius: 6px; 
      padding: 16px; 
      text-align: center; 
      margin-bottom: 20px; 
      backdrop-filter: blur(4px);
    }
    .caixa h2 { font-size: 0.95rem; margin-bottom: 12px; color: #94a3b8; text-transform: uppercase; }

    .slots { display: flex; justify-content: center; gap: 12px; flex-wrap: wrap; }
    .slot { width: 100px; height: 130px; background: #0f172a; border: 2px dashed #334155; border-radius: 6px; display: flex; flex-direction: column; align-items: center; justify-content: center; cursor: pointer; }
    .slot.destaque { border-color: #38bdf8; background: #1e293b; }
    .slot.preenchido img { width: 100%; height: 100%; object-fit: cover; }
    .slot span { font-size: 0.75rem; color: #64748b; }

    .grade-cards { display: grid; grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); gap: 10px; }
    .card { aspect-ratio: 3/4; background: #0f172a; border: 1px solid #334155; border-radius: 6px; overflow: hidden; cursor: grab; display: flex; flex-direction: column; align-items: center; justify-content: space-between; padding-top: 6px; }
    .card:hover { border-color: #38bdf8; }
    .card span { font-size: 0.7rem; font-weight: bold; text-transform: uppercase; }
    .card img { width: 100%; height: calc(100% - 18px); object-fit: cover; pointer-events: none; }
    .card.sumido { display: none !important; }

    .botao-jogar { background: #2563eb; color: #fff; border: none; padding: 10px 20px; font-weight: bold; border-radius: 4px; cursor: pointer; text-transform: uppercase; font-size: 0.85rem; margin-top: 15px; }
    .botao-jogar:hover { background: #1d4ed8; }
  </style>
</head>
<body>

  <header>
    <h1>MonstroBolso</h1>
    <button class="voltar" onclick="window.location.href='site_jogo.php'">Voltar ao Menu</button>
  </header>

  <div class="caixa">
    <h2>Seu Time (Escolha 3 monstros)</h2>
    <div class="slots">
      <div class="slot" id="s1" data-num="1" ondragover="segurar(event)" ondragleave="tirarDestaque(event)" ondrop="soltar(event)" onclick="limparSlot(this)"><span>Slot 1</span></div>
      <div class="slot" id="s2" data-num="2" ondragover="segurar(event)" ondragleave="tirarDestaque(event)" ondrop="soltar(event)" onclick="limparSlot(this)"><span>Slot 2</span></div>
      <div class="slot" id="s3" data-num="3" ondragover="segurar(event)" ondragleave="tirarDestaque(event)" ondrop="soltar(event)" onclick="limparSlot(this)"><span>Slot 3</span></div>
    </div>
    
    <form id="meuForm" method="POST" action="">
      <input type="hidden" name="time_selecionado" id="inputEscondido">
      <button type="button" class="botao-jogar" onclick="enviarTime()">Bora pra Batalha!</button>
    </form>
  </div>

  <div class="caixa">
    <h2>Monstros Disponíveis</h2>
    <div class="grade-cards">
      <!-- Todos os 9 bichos do trampo -->
      <div class="card" id="rubizard" draggable="true" ondragstart="pegarCard(event)" onclick="clicarCard(this)" data-img="imagens-personagens/rubizard.png" data-nome="Rubizard" data-tipo="fogo"><span>Rubizard</span><img src="imagens-personagens/rubizard.png"></div>
      <div class="card" id="nilldrill" draggable="true" ondragstart="pegarCard(event)" onclick="clicarCard(this)" data-img="imagens-personagens/nilldrill.png" data-nome="Nilldrill" data-tipo="planta"><span>Nilldrill</span><img src="imagens-personagens/nilldrill.png"></div>
      <div class="card" id="vianalax" draggable="true" ondragstart="pegarCard(event)" onclick="clicarCard(this)" data-img="imagens-personagens/vianalax.png" data-nome="Vianalax" data-tipo="normal"><span>Vianalax</span><img src="imagens-personagens/vianalax.png"></div>
      <div class="card" id="violetwo" draggable="true" ondragstart="pegarCard(event)" onclick="clicarCard(this)" data-img="imagens-personagens/violetwo.png" data-nome="Violetwo" data-tipo="psiquico"><span>Violetwo</span><img src="imagens-personagens/violetwo.png"></div>
      <div class="card" id="darkvi" draggable="true" ondragstart="pegarCard(event)" onclick="clicarCard(this)" data-img="imagens-personagens/darkvi.png" data-nome="Darkvi" data-tipo="dark"><span>Darkvi</span><img src="imagens-personagens/darkvi.png"></div>
      <div class="card" id="brrpatana" draggable="true" ondragstart="pegarCard(event)" onclick="clicarCard(this)" data-img="imagens-personagens/brrpatana.png" data-nome="Brrpatana" data-tipo="agua"><span>Brrpatana</span><img src="imagens-personagens/brrpatana.png"></div>
      <div class="card" id="meloetta" draggable="true" ondragstart="pegarCard(event)" onclick="clicarCard(this)" data-img="imagens-personagens/meloetta.png" data-nome="Meloetta" data-tipo="normal"><span>Meloetta</span><img src="imagens-personagens/meloetta.png"></div>
      <div class="card" id="dragofav" draggable="true" ondragstart="pegarCard(event)" onclick="clicarCard(this)" data-img="imagens-personagens/dragofav.png" data-nome="Dragofav" data-tipo="dragao"><span>Dragofav</span><img src="imagens-personagens/dragofav.png"></div>
      <div class="card" id="velostervroom" draggable="true" ondragstart="pegarCard(event)" onclick="clicarCard(this)" data-img="imagens-personagens/velostervroom.png" data-nome="Velostervroom" data-tipo="aco"><span>Velostervroom</span><img src="imagens-personagens/velostervroom.png"></div>
    </div>
  </div>

  <script>
    let cardSendoArrastado = "";
    let timeVelho = <?php echo json_encode($meuTimeSalvo); ?>;

    // Se o mano já tinha time salvo, bota nos slots de novo quando a página abre
    window.onload = function() {
      if (timeVelho && timeVelho.length > 0) {
        timeVelho.forEach((bicho, i) => {
          let slotzinho = document.getElementById(`s${i + 1}`);
          // Acha o card pelo nome em letra minúscula
          let cardzinho = document.getElementById(bicho.nome.toLowerCase());
          if (slotzinho && cardzinho) {
            colocarNoSlot(slotzinho, cardzinho.id);
          }
        });
      }
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
      let cardId = cardEl.id;
      let slots = document.querySelectorAll('.slot');
      for (let s of slots) {
        if (!s.classList.contains('preenchido')) {
          colocarNoSlot(s, cardId);
          break;
        }
      }
    }

    function colocarNoSlot(slot, cardId) {
      let cardEl = document.getElementById(cardId);
      if (!cardEl) return;
      slot.classList.add('preenchido');
      slot.setAttribute('data-card-id', cardId);
      slot.setAttribute('data-nome', cardEl.getAttribute('data-nome'));
      slot.setAttribute('data-img', cardEl.getAttribute('data-img'));
      slot.setAttribute('data-tipo', cardEl.getAttribute('data-tipo'));
      slot.innerHTML = `<img src="${cardEl.getAttribute('data-img')}" alt="bicho">`;
      cardEl.classList.add('sumido');
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
        slot.innerHTML = `<span>Slot ${num}</span>`;
      }
    }

    function enviarTime() {
      let preenchidos = document.querySelectorAll('.slot.preenchido');
      if (preenchidos.length < 3) {
        alert("Epa! Escolhe 3 monstros antes de ir batalhar!");
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