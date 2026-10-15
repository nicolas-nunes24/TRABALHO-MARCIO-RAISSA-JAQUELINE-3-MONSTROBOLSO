<?php
  // Abre a sessão
  session_start();

  // Se o mano tentar entrar direto aqui sem escolher time, manda pro monte_time.php
 if (!isset($_SESSION['meu_time']) || empty($_SESSION['meu_time'])) {
      header("Location: monte_time.php");
      exit;
  }

  $meuTime =$_SESSION['meu_time'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Arena - MonstroBolso</title>
  <style>
    /* Estilo da tela de porrada */
    * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Courier New', Courier, monospace; }
    body { background-color: #121417; color: #fff; display: flex; flex-direction: column; align-items: center; padding: 20px; min-height: 100vh; justify-content: center; }

    .arena-caixa { width: 100%; max-width: 680px; background-color: #2b3a26; border: 4px solid #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 8px 24px rgba(0,0,0,0.6); }
    .cenario { position: relative; height: 300px; background: linear-gradient(to bottom, #72c5ef 0%, #a4e0a2 60%, #59a657 100%); padding: 15px; }

    .caixa-status { background-color: #f8f9fa; color: #111; border: 2px solid #333; padding: 10px; border-radius: 6px; width: 210px; position: absolute; font-size: 0.85rem; }
    .status-inimigo { top: 15px; left: 15px; }
    .status-jogador { bottom: 15px; right: 15px; }
    .topo-status { display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; font-weight: bold; }
    .texto-hp { font-size: 0.75rem; color: #555; }

    .fundo-barra { width: 100%; height: 8px; background-color: #cbd5e1; border-radius: 4px; overflow: hidden; border: 1px solid #94a3b8; }
    .cheia-barra { height: 100%; width: 100%; background-color: #22c55e; transition: width 0.3s ease; }

    .boneco { position: absolute; width: 110px; height: 110px; display: flex; align-items: center; justify-content: center; }
    .boneco-inimigo { top: 30px; right: 50px; }
    .boneco-jogador { bottom: 15px; left: 50px; }
    .boneco img { width: 100%; height: 100%; object-fit: contain; image-rendering: pixelated; }

    .painel-baixo { background-color: #1e293b; border-top: 4px solid #fff; display: flex; height: 130px; }
    .caixa-texto { flex: 1; padding: 15px; font-size: 0.90rem; line-height: 1.4; border-right: 2px solid #334155; overflow-y: auto; }

    .menu-botoes { width: 240px; display: grid; grid-template-columns: 1fr 1fr; gap: 6px; padding: 10px; background-color: #0f172a; }
    .botao-acao { background-color: #1e293b; color: #fff; border: 2px solid #334155; border-radius: 4px; font-weight: bold; font-size: 0.75rem; cursor: pointer; }
    .botao-acao:hover { background-color: #2563eb; border-color: #38bdf8; }

    .botoes-topo { margin-top: 15px; width: 100%; max-width: 680px; display: flex; justify-content: space-between; }
    .voltar-menu { background: #1e293b; color: #94a3b8; border: 1px solid #334155; padding: 8px 16px; border-radius: 4px; cursor: pointer; font-size: 0.85rem; }
  </style>
</head>
<body>

  <div class="arena-caixa">
    <div class="cenario">
      
      <!-- Status do Inimigo -->
      <div class="caixa-status status-inimigo">
        <div class="topo-status">
          <span id="nomeInimigo">Inimigo</span>
          <span class="texto-hp" id="hpInimigoTexto">100/100</span>
        </div>
        <div class="fundo-barra">
          <div class="cheia-barra" id="barraInimigo" style="width: 100%;"></div>
        </div>
      </div>

      <div class="boneco boneco-inimigo">
        <img id="imgInimigo" src="" alt="Inimigo">
      </div>

      <!-- Status do Jogador -->
      <div class="boneco boneco-jogador">
        <img id="imgJogador" src="" alt="Jogador">
      </div>

      <div class="caixa-status status-jogador">
        <div class="topo-status">
          <span id="nomeJogador"></span>
          <span class="texto-hp" id="hpJogadorTexto">100/100</span>
        </div>
        <div class="fundo-barra">
          <div class="cheia-barra" id="barraJogador" style="width: 100%;"></div>
        </div>
      </div>

    </div>

    <!-- Controles de batalha -->
    <div class="painel-baixo">
      <div class="caixa-texto" id="falaDoJogo">A batalha vai começar!</div>
      <div class="menu-botoes">
        <button class="botao-acao" onclick="atacar('Investida', 20, 'normal')">Atacar</button>
        <button class="botao-acao" onclick="abrirTroca()">Trocar</button>
        <button class="botao-acao" onclick="falar('Mochila tá vazia, mano!')">Mochila</button>
        <button class="botao-acao" onclick="irParaMontagem()">Fugir</button>
      </div>
    </div>
  </div>

  <div class="botoes-topo">
    <button class="voltar-menu" onclick="irParaMontagem()">Mudar Time</button>
    <button class="voltar-menu" onclick="window.location.href='site_jogo.php'">Menu Principal</button>
  </div>

  <script>
    // Pega o time que veio da sessão
    let meuTime = <?php echo json_encode($meuTime); ?>;
    let vezDoBicho = 0;

    // Inimigo fixo pra brincar na luta
    let oponente = {
      nome: "Rubizard",
      tipo: "fogo",
      img: "imagens-personagens/rubizard.png",
      hpMax: 100,
      hpAtual: 100
    };

    let travado = false;

    // Tabela simples de vantagens de tipos (estilo pokemon)
    let vantagens = {
      'fogo': { 'agua': 0.5, 'fogo': 0.5, 'planta': 2.0, 'normal': 1.0, 'psiquico': 1.0, 'dark': 1.0, 'dragao': 1.0, 'aco': 1.0 },
      'agua': { 'fogo': 2.0, 'agua': 0.5, 'planta': 0.5, 'normal': 1.0, 'psiquico': 1.0, 'dark': 1.0, 'dragao': 1.0, 'aco': 1.0 },
      'planta': { 'fogo': 0.5, 'agua': 2.0, 'planta': 0.5, 'normal': 1.0, 'psiquico': 1.0, 'dark': 1.0, 'dragao': 1.0, 'aco': 1.0 },
      'normal': { 'fogo': 1.0, 'agua': 1.0, 'planta': 1.0, 'normal': 1.0, 'psiquico': 1.0, 'dark': 1.0, 'dragao': 1.0, 'aco': 1.0 }
    };

    function iniciarBatalha() {
      let bichoAtual = meuTime[vezDoBicho];
      document.getElementById('nomeJogador').innerText = bichoAtual.nome;
      document.getElementById('imgJogador').src = bichoAtual.img;
      document.getElementById('barraJogador').style.width = (bichoAtual.hp / 100 * 100) + '%';
      document.getElementById('hpJogadorTexto').innerText = bichoAtual.hp + '/100';

      document.getElementById('nomeInimigo').innerText = oponente.nome;
      document.getElementById('imgInimigo').src = oponente.img;
      document.getElementById('barraInimigo').style.width = (opone.hpAtual / oponente.hpMax * 100) + '%';
      document.getElementById('hpInimigoTexto').innerText = oponente.hpAtual + '/' + oponente.hpMax;

      falar(`Um ${oponente.nome} selvagem apareceu! Vai lá, ${bichoAtual.nome}!`);
    }

    // Ajuste pro HP do oponente não dar conflito no código
    // (correção rápida pra variável oponente)
    function atualizarHpInimigo() {
      document.getElementById('barraInimigo').style.width = (oponente.hpAtual / oponente.hpMax * 100) + '%';
      document.getElementById('hpInimigoTexto').innerText = oponente.hpAtual + '/' + oponente.hpMax;
    }

    function irParaMontagem() {
      window.location.href = "monte_time.php";
    }

    function falar(texto) {
      document.getElementById('falaDoJogo').innerText = texto;
    }

    function atacar(nomeGolpe, danoBase, tipoGolpe) {
      let bichoAtual = meuTime[vezDoBicho];
      if (travado || bichoAtual.hp <= 0 || oponente.hpAtual <= 0) return;
      travado = true;

      let mult = vantagens[tipoGolpe]?.[oponente.tipo] || 1.0;
      let danoFinal = Math.floor(danoBase * mult);
      
      oponente.hpAtual -= danoFinal;
      if (oponente.hpAtual < 0) oponente.hpAtual = 0;
      atualizarHpInimigo();

      let frase = `${bichoAtual.nome} usou ${nomeGolpe}! Causou ${danoFinal} de dano.`;
      if (mult > 1) frase += " Foi super efetivo!";
      falar(frase);

      if (oponente.hpAtual === 0) {
        setTimeout(() => { alert("Boa! Você mandou mal no oponente e venceu!"); irParaMontagem(); }, 1200);
        return;
      }

      setTimeout(() => { vezDoBot(); }, 1500);
    }

    function abrirTroca() {
      let opcoes = "Escolha outro bicho do time:\n";
      meuTime.forEach((b, i) => {
        if (i !== vezDoBicho && b.hp > 0) opcoes += `[${i + 1}] ${b.nome} (HP: ${b.hp})\n`;
      });
      let escolha = prompt(opcoes);
      let novoIndice = parseInt(escolha) - 1;
      if (!isNaN(novoIndice) && meuTime[novoIndice] && novoIndice !== vezDoBicho && meuTime[novoIndice].hp > 0) {
        vezDoBicho = novoIndice;
        iniciarBatalha();
        falar(`Sai ${meuTime[vezDoBicho].nome}, entra você!`);
      } else {
        falar("Escolha inválida ou bicho sem vida!");
      }
    }

    function vezDoBot() {
      let bichoAtual = meuTime[vezDoBicho];
      if (bichoAtual.hp <= 0) return;

      let danoSofrido = 20;
      bichoAtual.hp -= danoSofrido;
      if (bichoAtual.hp < 0) bichoAtual.hp = 0;

      document.getElementById('barraJogador').style.width = (bichoAtual.hp / 100 * 100) + '%';
      document.getElementById('hpJogadorTexto').innerText = bichoAtual.hp + '/100';

      falar(`O inimigo revidou e tirou ${danoSofrido} de HP do ${bichoAtual.nome}!`);

      if (bichoAtual.hp === 0) {
        setTimeout(() => {
          alert(`Eita, o ${bichoAtual.nome} desmaiou!`);
          let proximoVivo = meuTime.findIndex(b => b.hp > 0);
          if (proximoVivo !== -1) {
            vezDoBicho = proximoVivo;
            iniciarBatalha();
            travado = false;
          } else {
            alert("Vish, teu time inteiro foi de arrasta pra cima! Fim de jogo.");
            irParaMontagem();
          }
        }, 1000);
        return;
      }

      setTimeout(() => { falar("Sua vez de mandar bala!"); travado = false; }, 1500);
    }

    window.onload = function() {
      iniciarBatalha();
    };
  </script>

</body>
</html>