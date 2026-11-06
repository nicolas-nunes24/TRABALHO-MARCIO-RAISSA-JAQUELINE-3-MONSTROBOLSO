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
  
    /* Imagem de fundo da pagina e do cenario da luta */
    body::before { content: ""; position: fixed; inset: 0; z-index: -1; background: url("cenario_luta.jpg") center / cover no-repeat; filter: blur(6px) brightness(0.45); transform: scale(1.03); }
    .cenario { background: url("cenario_luta.jpg") center 75% / cover no-repeat; }
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
      
      <!-- Menu de Ações com os 3 ataques dinâmicos -->
      <div class="menu-botoes">
        <button class="botao-acao" id="btnAtaque1">Investida</button>
        <button class="botao-acao" id="btnAtaque2">Elemental</button>
        <button class="botao-acao" id="btnAtaque3">Debuff</button>
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

</body>

<script>
    // Pega o time vindo da sessão PHP
    let meuTime = <?php echo json_encode($meuTime); ?>;
    let vezDoBicho = 0;

    // Catálogo completo com os Pokémon das imagens
    const pokesDisponiveis = [
      { nome: "Rubizard", tipo: "fogo", nivel: 50, hpMax: 80, hpAtual: 80, ataque: 84, defesa: 78, fraquezas: ["agua", "smoke", "alcohol"], img: "imagens-personagens/rubizard.png" },
      { nome: "Jynxtetoi", tipo: "ice", nivel: 50, hpMax: 95, hpAtual: 95, ataque: 50, defesa: 35, fraquezas: ["fogo", "bug", "rock", "ghost", "dark", "steel", "dvd"], img: "imagens-personagens/jynxtetoi.png" },
      { nome: "Meloetta", tipo: "normal", nivel: 50, hpMax: 100, hpAtual: 100, ataque: 77, defesa: 77, fraquezas: ["bug", "dark"], img: "imagens-personagens/meloetta.png" },
      { nome: "Nilldril", tipo: "bug", nivel: 50, hpMax: 65, hpAtual: 65, ataque: 90, defesa: 40, fraquezas: ["fogo", "flying", "ground", "rock", "psiquico"], img: "imagens-personagens/nilldril.png" },
      { nome: "Velostervroom", tipo: "steel", nivel: 50, hpMax: 80, hpAtual: 80, ataque: 119, defesa: 90, fraquezas: ["fogo", "ground"], img: "imagens-personagens/velostervroom.png" },
      { nome: "Vianalax", tipo: "normal", nivel: 50, hpMax: 160, hpAtual: 160, ataque: 110, defesa: 65, fraquezas: ["luta", "buzz"], img: "imagens-personagens/vianalax.png" },
      { nome: "Violetwo", tipo: "planta", nivel: 50, hpMax: 106, hpAtual: 106, ataque: 110, defesa: 90, fraquezas: ["bug", "ghost", "dark"], img: "imagens-personagens/violetwo.png" },
      { nome: "Brrpatana", tipo: "agua", nivel: 50, hpMax: 70, hpAtual: 70, ataque: 52, defesa: 48, fraquezas: ["planta", "electric", "nico", "rd"], img: "imagens-personagens/brrpatana.png" },
      { nome: "Darkvi", tipo: "dark", nivel: 50, hpMax: 70, hpAtual: 70, ataque: 90, defesa: 90, fraquezas: ["luta", "bug", "fada", "ruivas", "donw"], img: "imagens-personagens/darkvi.png" },
      { nome: "Dragofav", tipo: "dragao", nivel: 50, hpMax: 91, hpAtual: 91, ataque: 134, defesa: 95, fraquezas: ["rock", "ice", "dragao", "fada", "jl"], img: "imagens-personagens/dragofav.png" }
    ];

    let timeInimigo = [];
    let vezDoInimigo = 0;
    let travado = false;

    // Tabela padrão de tipos
    const vantagens = {
      'fogo': { 'fogo': 0.5, 'agua': 0.5, 'planta': 2.0, 'normal': 1.0, 'ice': 2.0, 'bug': 2.0, 'steel': 2.0 },
      'agua': { 'fogo': 2.0, 'agua': 0.5, 'planta': 0.5, 'normal': 1.0, 'rock': 2.0, 'ground': 2.0 },
      'planta': { 'fogo': 0.5, 'agua': 2.0, 'planta': 0.5, 'normal': 1.0, 'rock': 2.0, 'ground': 2.0 },
      'normal': { 'fogo': 1.0, 'agua': 1.0, 'planta': 1.0, 'normal': 1.0 },
      'ice': { 'fogo': 0.5, 'agua': 0.5, 'planta': 2.0, 'dragao': 2.0, 'flying': 2.0, 'ground': 2.0 },
      'dark': { 'psiquico': 2.0, 'ghost': 2.0, 'dark': 0.5, 'luta': 0.5 },
      'bug': { 'planta': 2.0, 'psiquico': 2.0, 'dark': 2.0, 'fogo': 0.5 },
      'dragao': { 'dragao': 2.0, 'steel': 0.5 },
      'steel': { 'rock': 2.0, 'ice': 2.0, 'fada': 2.0, 'steel': 0.5, 'fogo': 0.5, 'agua': 0.5 }
    };

    // Golpes base
    const bibliotecaGolpes = {
      fogo: { elemental: { nome: "Lança-Chamas", poder: 90, precisao: 100, tipo: "fogo" }, debuff: { nome: "Fumaça Densa", poder: 0, precisao: 100, tipo: "fogo", efeito: "redutAtaque" } },
      agua: { elemental: { nome: "Hidro Bomba", poder: 90, precisao: 85, tipo: "agua" }, debuff: { nome: "Jacto Enfraquecedor", poder: 40, precisao: 100, tipo: "agua", efeito: "redutAtaque" } },
      planta: { elemental: { nome: "Chicote de Vinha", poder: 80, precisao: 100, tipo: "planta" }, debuff: { nome: "Pó de Esporos", poder: 0, precisao: 90, tipo: "planta", efeito: "redutDefesa" } },
      normal: { elemental: { nome: "Golpe de Corpo", poder: 85, precisao: 100, tipo: "normal" }, debuff: { nome: "Encarar", poder: 0, precisao: 100, tipo: "normal", efeito: "redutDefesa" } },
      ice: { elemental: { nome: "Raio de Gelo", poder: 90, precisao: 100, tipo: "ice" }, debuff: { nome: "Vento Gelado", poder: 35, precisao: 95, tipo: "ice", efeito: "redutAtaque" } },
      dark: { elemental: { nome: "Pulso Sombrio", poder: 80, precisao: 100, tipo: "dark" }, debuff: { nome: "Provocar", poder: 0, precisao: 100, tipo: "dark", efeito: "redutDefesa" } },
      bug: { elemental: { nome: "Zumbido", poder: 90, precisao: 100, tipo: "bug" }, debuff: { nome: "Fio de Teia", poder: 0, precisao: 95, tipo: "bug", efeito: "redutDefesa" } },
      dragao: { elemental: { nome: "Garra Dragão", poder: 80, precisao: 100, tipo: "dragao" }, debuff: { nome: "Rugido Assustador", poder: 0, precisao: 100, tipo: "dragao", efeito: "redutAtaque" } },
      steel: { elemental: { nome: "Cabeçada de Aço", poder: 80, precisao: 100, tipo: "steel" }, debuff: { nome: "Som Metálico", poder: 0, precisao: 100, tipo: "steel", efeito: "redutDefesa" } }
    };

    const golpeInvestidaPadrao = { nome: "Investida", poder: 40, precisao: 100, tipo: "normal" };

    function gerarTimeInimigo() {
      let time = [];
      let copiaDisponiveis = [...pokesDisponiveis];
      for (let i = 0; i < 3; i++) {
        let idx = Math.floor(Math.random() * copiaDisponiveis.length);
        let pokeClonado = JSON.parse(JSON.stringify(copiaDisponiveis[idx]));
        pokeClonado.estagios = { ata: 0, def: 0 };
        time.push(pokeClonado);
        copiaDisponiveis.splice(idx, 1); // Evita Pokémon repetidos no mesmo time
      }
      return time;
    }

    function getMultiplicadorEstagio(estagio) {
      if (estagio >= 0) return (2 + estagio) / 2;
      return 2 / (2 - estagio);
    }

    function oponenteAtual() {
      return timeInimigo[vezDoInimigo];
    }

    function iniciarBatalha() {
      if (timeInimigo.length === 0) {
        timeInimigo = gerarTimeInimigo();
        vezDoInimigo = 0;
      }

      let bicho = meuTime[vezDoBicho];
      let op = oponenteAtual();

      bicho.nivel = bicho.nivel || 50;
      bicho.tipo = bicho.tipo || 'normal';
      bicho.ataque = bicho.ataque || bicho.forca || 50;
      bicho.defesa = bicho.defesa || 50;
      bicho.hpMax = bicho.hpMax || 100;
      bicho.hp = (bicho.hp !== undefined) ? bicho.hp : bicho.hpMax;
      bicho.estagios = bicho.estagios || { ata: 0, def: 0 };
      bicho.fraquezas = bicho.fraquezas || [];

      document.getElementById('nomeJogador').innerText = bicho.nome;
      document.getElementById('imgJogador').src = bicho.img;
      atualizarBarraHP('barraJogador', 'hpJogadorTexto', bicho.hp, bicho.hpMax);

      atualizarInterfaceInimigo();
      atualizarBotoesAtaque();
      falar(`Batalha iniciada! O oponente enviou ${op.nome}! Vai, ${bicho.nome}!`);
    }

    function atualizarInterfaceInimigo() {
      let op = oponenteAtual();
      document.getElementById('nomeInimigo').innerText = `${op.nome} (${vezDoInimigo + 1}/3)`;
      document.getElementById('imgInimigo').src = op.img;
      atualizarBarraHP('barraInimigo', 'hpInimigoTexto', op.hpAtual, op.hpMax);
    }

    function atualizarBarraHP(idBarra, idTexto, hpAtual, hpMax) {
      let porc = Math.max(0, (hpAtual / hpMax) * 100);
      document.getElementById(idBarra).style.width = porc + '%';
      document.getElementById(idTexto).innerText = `${Math.ceil(hpAtual)}/${hpMax}`;
    }

    function atualizarBotoesAtaque() {
      let bicho = meuTime[vezDoBicho];
      let tipo = bicho.tipo in bibliotecaGolpes ? bicho.tipo : 'normal';
      let golpes = bibliotecaGolpes[tipo];

      let btn1 = document.getElementById('btnAtaque1');
      let btn2 = document.getElementById('btnAtaque2');
      let btn3 = document.getElementById('btnAtaque3');

      if (btn1) {
        btn1.innerText = `${golpeInvestidaPadrao.nome} (Físico)`;
        btn1.onclick = () => executarTurnoShowdown(golpeInvestidaPadrao);
      }
      if (btn2) {
        btn2.innerText = `${golpes.elemental.nome} (${tipo.toUpperCase()})`;
        btn2.onclick = () => executarTurnoShowdown(golpes.elemental);
      }
      if (btn3) {
        btn3.innerText = `${golpes.debuff.nome} (Status)`;
        btn3.onclick = () => executarTurnoShowdown(golpes.debuff);
      }
    }

    // Calculadora com suporte a fraquezas personalizadas dos cartões
    function calcularDanoShowdown(atacante, defensor, golpe) {
      if (golpe.poder === 0) return { dano: 0, efec: 1, critico: false, errou: false };

      if (Math.random() * 100 > golpe.precisao) {
        return { dano: 0, efec: 1, critico: false, errou: true };
      }

      let atqReal = atacante.ataque * getMultiplicadorEstagio(atacante.estagios.ata);
      let defReal = defensor.defesa * getMultiplicadorEstagio(defensor.estagios.def);
      let danoBase = Math.floor(Math.floor((2 * atacante.nivel / 5 + 2) * golpe.poder * (atqReal / defReal)) / 50) + 2;

      let critico = Math.random() < 0.0625;
      let multCritico = critico ? 1.5 : 1.0;
      let rng = (Math.floor(Math.random() * 16) + 85) / 100;
      let stab = (atacante.tipo === golpe.tipo) ? 1.5 : 1.0;

      // Verifica fraqueza da lista do cartão do defensor ou da tabela de tipos
      let efec = 1.0;
      if (defensor.fraquezas && defensor.fraquezas.includes(golpe.tipo.toLowerCase())) {
        efec = 2.0;
      } else {
        efec = vantagens[golpe.tipo]?.[defensor.tipo] ?? 1.0;
      }

      let danoFinal = Math.floor(danoBase * multCritico * rng * stab * efec);
      return { dano: Math.max(1, danoFinal), efec: efec, critico: critico, errou: false };
    }

    function executarTurnoShowdown(golpeEscolhido) {
      let bichoAtual = meuTime[vezDoBicho];
      let op = oponenteAtual();

      if (travado || bichoAtual.hp <= 0 || op.hpAtual <= 0) return;
      travado = true;

      let resultado = calcularDanoShowdown(bichoAtual, op, golpeEscolhido);
      let log = "";

      if (resultado.errou) {
        log = `${bichoAtual.nome} usou ${golpeEscolhido.nome}, mas errou!`;
      } else {
        op.hpAtual -= resultado.dano;
        if (op.hpAtual < 0) op.hpAtual = 0;
        atualizarBarraHP('barraInimigo', 'hpInimigoTexto', op.hpAtual, op.hpMax);

        log = `${bichoAtual.nome} usou ${golpeEscolhido.nome}! `;
        if (resultado.dano > 0) log += `Causou ${resultado.dano} de dano. `;

        if (golpeEscolhido.efeito === 'redutAtaque' && op.estagios.ata > -6) {
          op.estagios.ata--;
          log += ` O Ataque de ${op.nome} caiu!`;
        } else if (golpeEscolhido.efeito === 'redutDefesa' && op.estagios.def > -6) {
          op.estagios.def--;
          log += ` A Defesa de ${op.nome} caiu!`;
        }

        if (resultado.critico) log += " Um golpe crítico!";
        if (resultado.efec > 1) log += " É super efetivo!";
        else if (resultado.efec < 1 && resultado.efec > 0) log += " Não é muito efetivo...";
      }

      falar(log);

      if (op.hpAtual === 0) {
        setTimeout(() => {
          let proximoInimigoVivo = timeInimigo.findIndex(b => b.hpAtual > 0);
          if (proximoInimigoVivo !== -1) {
            vezDoInimigo = proximoInimigoVivo;
            let novoOp = oponenteAtual();
            atualizarInterfaceInimigo();
            falar(`${op.nome} desmaiou! O oponente enviou ${novoOp.nome}!`);
            travado = false;
          } else {
            alert("Vitória esplêndida! Você derrotou todo o time oponente!");
            irParaMontagem();
          }
        }, 1300);
        return;
      }

      setTimeout(() => { vezDoBotShowdown(); }, 1600);
    }

    function vezDoBotShowdown() {
      let bichoAtual = meuTime[vezDoBicho];
      let op = oponenteAtual();

      if (bichoAtual.hp <= 0 || op.hpAtual <= 0) return;

      let tipoOp = op.tipo in bibliotecaGolpes ? op.tipo : 'normal';
      let golpeBot = Math.random() > 0.4 ? bibliotecaGolpes[tipoOp].elemental : golpeInvestidaPadrao;

      let resultado = calcularDanoShowdown(op, bichoAtual, golpeBot);
      let log = "";

      if (resultado.errou) {
        log = `${op.nome} tentou usar ${golpeBot.nome}, mas errou!`;
      } else {
        bichoAtual.hp -= resultado.dano;
        if (bichoAtual.hp < 0) bichoAtual.hp = 0;
        atualizarBarraHP('barraJogador', 'hpJogadorTexto', bichoAtual.hp, bichoAtual.hpMax);

        log = `${op.nome} usou ${golpeBot.nome} e causou ${resultado.dano} de dano!`;
        if (resultado.critico) log += " Foi um golpe crítico!";
        if (resultado.efec > 1) log += " Foi super efetivo!";
      }

      falar(log);

      if (bichoAtual.hp === 0) {
        setTimeout(() => {
          alert(`${bichoAtual.nome} desmaiou!`);
          let proximoVivo = meuTime.findIndex(b => b.hp > 0);
          if (proximoVivo !== -1) {
            vezDoBicho = proximoVivo;
            iniciarBatalha();
            travado = false;
          } else {
            alert("Você não tem mais Pokémon disponíveis! Fim de jogo.");
            irParaMontagem();
          }
        }, 1200);
        return;
      }

      setTimeout(() => { falar("Sua vez de jogar!"); travado = false; }, 1500);
    }

    function abrirTroca() {
      let opcoes = "Escolha um Pokémon do time para entrar:\n";
      meuTime.forEach((b, i) => {
        if (i !== vezDoBicho && b.hp > 0) opcoes += `[${i + 1}] ${b.nome} (HP: ${Math.ceil(b.hp)}/${b.hpMax || 100})\n`;
      });
      let escolha = prompt(opcoes);
      let novoIndice = parseInt(escolha) - 1;
      if (!isNaN(novoIndice) && meuTime[novoIndice] && novoIndice !== vezDoBicho && meuTime[novoIndice].hp > 0) {
        vezDoBicho = novoIndice;
        iniciarBatalha();
        falar(`Vai! ${meuTime[vezDoBicho].nome}!`);
      } else {
        falar("Escolha inválida!");
      }
    }

    function falar(texto) {
      document.getElementById('falaDoJogo').innerText = texto;
    }

    function irParaMontagem() {
      window.location.href = "monte_time.php";
    }

    window.onload = function() {
      iniciarBatalha();
    };
</script>
</body>
</html>