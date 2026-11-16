<?php
session_start();

if (empty($_SESSION['meu_time'])) {
    header("Location: monte_time.php");
    exit;
}

$meuTime = $_SESSION['meu_time'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Arena - MonstroBolso</title>
  <link rel="stylesheet" href="arenaJogo.css">
</head>
<body>

  <div class="botoes-topo">
    <button class="voltar-menu" onclick="irParaMontagem()">Mudar Time</button>
    <button class="voltar-menu" onclick="window.location.href='site_jogo.php'">Menu Principal</button>
  </div>

  <div class="arena-caixa">
    <div class="cenario">
      <!-- Status e Sprite do Inimigo -->
      <div class="caixa-status status-inimigo">
        <div class="topo-status">
          <span id="nomeInimigo">Inimigo</span>
          <span class="texto-hp" id="hpInimigoTexto">100/100</span>
        </div>
        <div class="fundo-barra">
          <div class="cheia-barra" id="barraInimigo"></div>
        </div>
      </div>

      <div class="boneco boneco-inimigo">
        <img id="imgInimigo" src="" alt="Inimigo">
      </div>

      <!-- Sprite e Status do Jogador -->
      <div class="boneco boneco-jogador">
        <img id="imgJogador" src="" alt="Jogador">
      </div>

      <div class="caixa-status status-jogador">
        <div class="topo-status">
          <span id="nomeJogador"></span>
          <span class="texto-hp" id="hpJogadorTexto">100/100</span>
        </div>
        <div class="fundo-barra">
          <div class="cheia-barra" id="barraJogador"></div>
        </div>
      </div>
    </div>

    <!-- Controles de batalha -->
    <div class="painel-baixo">
      <div class="caixa-texto" id="falaDoJogo">A batalha vai começar!</div>
      
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

  <script>
  const $ = id => document.getElementById(id);
  const falar = txt => $('falaDoJogo').innerText = txt;
  const irParaMontagem = () => window.location.href = "monte_time.php";
  const multEstagio = e => e >= 0 ? (2 + e) / 2 : 2 / (2 - e);

  const pokesDisponiveis = [
    { nome: "Rubizard", tipo: "fogo", nivel: 50, hpMax: 80, ataque: 84, defesa: 78, fraquezas: ["agua", "smoke", "alcohol"], img: "imagens-personagens/rubizard.png" },
    { nome: "Jynxtetoi", tipo: "ice", nivel: 50, hpMax: 95, ataque: 50, defesa: 35, fraquezas: ["fogo", "bug", "rock", "ghost", "dark", "steel", "dvd"], img: "imagens-personagens/jynxtetoi.png" },
    { nome: "Meloetta", tipo: "normal", nivel: 50, hpMax: 100, ataque: 77, defesa: 77, fraquezas: ["bug", "dark"], img: "imagens-personagens/meloetta.png" },
    { nome: "Nilldril", tipo: "bug", nivel: 50, hpMax: 65, ataque: 90, defesa: 40, fraquezas: ["fogo", "flying", "ground", "rock", "psiquico"], img: "imagens-personagens/nilldril.png" },
    { nome: "Velostervroom", tipo: "steel", nivel: 50, hpMax: 80, ataque: 119, defesa: 90, fraquezas: ["fogo", "ground"], img: "imagens-personagens/velostervroom.png" },
    { nome: "Vianalax", tipo: "normal", nivel: 50, hpMax: 160, ataque: 110, defesa: 65, fraquezas: ["luta", "buzz"], img: "imagens-personagens/vianalax.png" },
    { nome: "Violetwo", tipo: "planta", nivel: 50, hpMax: 106, ataque: 110, defesa: 90, fraquezas: ["bug", "ghost", "dark"], img: "imagens-personagens/violetwo.png" },
    { nome: "Brrpatana", tipo: "agua", nivel: 50, hpMax: 70, ataque: 52, defesa: 48, fraquezas: ["planta", "electric", "nico", "rd"], img: "imagens-personagens/brrpatana.png" },
    { nome: "Darkvi", tipo: "dark", nivel: 50, hpMax: 70, ataque: 90, defesa: 90, fraquezas: ["luta", "bug", "fada", "ruivas", "donw"], img: "imagens-personagens/darkvi.png" },
    { nome: "Dragofav", tipo: "dragao", nivel: 50, hpMax: 91, ataque: 134, defesa: 95, fraquezas: ["rock", "ice", "dragao", "fada", "jl"], img: "imagens-personagens/dragofav.png" }
  ];

  const vantagens = {
    fogo: { fogo: 0.5, agua: 0.5, planta: 2, normal: 1, ice: 2, bug: 2, steel: 2 },
    agua: { fogo: 2, agua: 0.5, planta: 0.5, normal: 1, rock: 2, ground: 2 },
    planta: { fogo: 0.5, agua: 2, planta: 0.5, normal: 1, rock: 2, ground: 2 },
    normal: { fogo: 1, agua: 1, planta: 1, normal: 1 },
    ice: { fogo: 0.5, agua: 0.5, planta: 2, dragao: 2, flying: 2, ground: 2 },
    dark: { psiquico: 2, ghost: 2, dark: 0.5, luta: 0.5 },
    bug: { planta: 2, psiquico: 2, dark: 2, fogo: 0.5 },
    dragao: { dragao: 2, steel: 0.5 },
    steel: { rock: 2, ice: 2, fada: 2, steel: 0.5, fogo: 0.5, agua: 0.5 }
  };

  // 1. Cada golpe agora possui a propriedade 'pp'
  const bibliotecaGolpes = {
    fogo:   { elemental: { nome: "Lança-Chamas", poder: 90, precisao: 100, tipo: "fogo", pp: 15 }, debuff: { nome: "Fumaça Densa", poder: 0, precisao: 100, tipo: "fogo", efeito: "ata", pp: 20 } },
    agua:   { elemental: { nome: "Hidro Bomba", poder: 90, precisao: 85, tipo: "agua", pp: 10 }, debuff: { nome: "Jacto Enfraquecedor", poder: 40, precisao: 100, tipo: "agua", efeito: "ata", pp: 20 } },
    planta: { elemental: { nome: "Chicote de Vinha", poder: 80, precisao: 100, tipo: "planta", pp: 15 }, debuff: { nome: "Pó de Esporos", poder: 0, precisao: 90, tipo: "planta", efeito: "def", pp: 20 } },
    normal: { elemental: { nome: "Golpe de Corpo", poder: 85, precisao: 100, tipo: "normal", pp: 15 }, debuff: { nome: "Encarar", poder: 0, precisao: 100, tipo: "normal", efeito: "def", pp: 20 } },
    ice:    { elemental: { nome: "Raio de Gelo", poder: 90, precisao: 100, tipo: "ice", pp: 15 }, debuff: { nome: "Vento Gelado", poder: 35, precisao: 95, tipo: "ice", efeito: "ata", pp: 20 } },
    dark:   { elemental: { nome: "Pulso Sombrio", poder: 80, precisao: 100, tipo: "dark", pp: 15 }, debuff: { nome: "Provocar", poder: 0, precisao: 100, tipo: "dark", efeito: "def", pp: 20 } },
    bug:    { elemental: { nome: "Zumbido", poder: 90, precisao: 100, tipo: "bug", pp: 15 }, debuff: { nome: "Fio de Teia", poder: 0, precisao: 95, tipo: "bug", efeito: "def", pp: 20 } },
    dragao: { elemental: { nome: "Garra Dragão", poder: 80, precisao: 100, tipo: "dragao", pp: 15 }, debuff: { nome: "Rugido Assustador", poder: 0, precisao: 100, tipo: "dragao", efeito: "ata", pp: 20 } },
    steel:  { elemental: { nome: "Cabeçada de Aço", poder: 80, precisao: 100, tipo: "steel", pp: 15 }, debuff: { nome: "Som Metálico", poder: 0, precisao: 100, tipo: "steel", efeito: "def", pp: 20 } }
  };

  const golpeInvestida = { nome: "Investida", poder: 40, precisao: 100, tipo: "normal", pp: 35 };
  const golpeCombate = { nome: "Combate", poder: 50, precisao: 100, tipo: "normal", infinito: true };

  // 2. Clona os 3 golpes individualmente para cada Pokémon com ppAtual e ppMax
  const normalizarPoke = p => {
    const tipo = p.tipo || "normal";
    const set = bibliotecaGolpes[tipo] || bibliotecaGolpes.normal;
    const golpes = p.golpes || [golpeInvestida, set.elemental, set.debuff].map(g => ({
      ...g,
      ppMax: g.pp,
      ppAtual: g.pp
    }));

    return {
      ...p,
      nivel: p.nivel || 50,
      tipo,
      ataque: p.ataque || p.forca || 50,
      defesa: p.defesa || 50,
      hpMax: p.hpMax || 100,
      hp: p.hp ?? p.hpAtual ?? p.hpMax ?? 100,
      fraquezas: p.fraquezas || [],
      estagios: p.estagios || { ata: 0, def: 0 },
      golpes
    };
  };

  let meuTime = (<?php echo json_encode($meuTime); ?>).map(normalizarPoke);
  let timeInimigo = [...pokesDisponiveis].sort(() => Math.random() - 0.5).slice(0, 3).map(normalizarPoke);
  let vezDoBicho = 0;
  let vezDoInimigo = 0;
  let travado = false;

  function atualizarBarraHP(idBarra, idTexto, hp, hpMax) {
    const porc = Math.max(0, (hp / hpMax) * 100);
    const barra = $(idBarra);
    barra.style.width = porc + '%';
    barra.style.backgroundColor = porc > 50 ? '#22c55e' : porc > 20 ? '#eab308' : '#ef4444';
    $(idTexto).innerText = `${Math.ceil(hp)}/${hpMax}`;
  }

  // 3. Atualiza os botões com o contador de PP e bloqueia os que chegaram a 0
  function atualizarArena() {
    const bicho = meuTime[vezDoBicho];
    const op = timeInimigo[vezDoInimigo];

    $('nomeJogador').innerText = bicho.nome;
    $('imgJogador').src = bicho.img;
    atualizarBarraHP('barraJogador', 'hpJogadorTexto', bicho.hp, bicho.hpMax);

    $('nomeInimigo').innerText = `${op.nome} (${vezDoInimigo + 1}/3)`;
    $('imgInimigo').src = op.img;
    atualizarBarraHP('barraInimigo', 'hpInimigoTexto', op.hp, op.hpMax);

    const botoes = [$('btnAtaque1'), $('btnAtaque2'), $('btnAtaque3')];
    const semPP = bicho.golpes.every(g => g.ppAtual <= 0);

    if (semPP) {
      botoes[0].innerText = "Combate (Sem PP)";
      botoes[0].disabled = false;
      botoes[0].onclick = () => executarTurno(golpeCombate);

      botoes.slice(1).forEach(btn => {
        btn.innerText = "--- [0/0]";
        btn.disabled = true;
      });
      return;
    }

    bicho.golpes.forEach((golpe, i) => {
      const btn = botoes[i];
      btn.innerText = `${golpe.nome} [${golpe.ppAtual}/${golpe.ppMax}]`;
      btn.disabled = golpe.ppAtual <= 0;
      btn.onclick = () => executarTurno(golpe);
    });
  }

  function calcularDano(atacante, defensor, golpe) {
    if (Math.random() * 100 > golpe.precisao) return { errou: true };
    if (golpe.poder === 0) return { dano: 0, efec: 1, critico: false };

    const atq = atacante.ataque * multEstagio(atacante.estagios.ata);
    const def = defensor.defesa * multEstagio(defensor.estagios.def);
    const base = Math.floor(Math.floor((2 * atacante.nivel / 5 + 2) * golpe.poder * (atq / def)) / 50) + 2;

    const critico = Math.random() < 0.0625;
    const rng = (Math.floor(Math.random() * 16) + 85) / 100;
    const stab = atacante.tipo === golpe.tipo ? 1.5 : 1;
    const efec = defensor.fraquezas.includes(golpe.tipo.toLowerCase())
      ? 2
      : (vantagens[golpe.tipo]?.[defensor.tipo] ?? 1);

    const dano = Math.max(1, Math.floor(base * (critico ? 1.5 : 1) * rng * stab * efec));
    return { dano, efec, critico };
  }

  // 4. Consome 1 PP ao usar o golpe e atualiza o texto do botão
  function aplicarGolpe(atacante, defensor, golpe, alvoEhInimigo) {
    if (!golpe.infinito && golpe.ppAtual > 0) {
      golpe.ppAtual--;
      atualizarArena();
    }

    const res = calcularDano(atacante, defensor, golpe);
    if (res.errou) {
      falar(`${atacante.nome} usou ${golpe.nome}, mas errou!`);
      return;
    }

    defensor.hp = Math.max(0, defensor.hp - res.dano);
    atualizarBarraHP(
      alvoEhInimigo ? 'barraInimigo' : 'barraJogador',
      alvoEhInimigo ? 'hpInimigoTexto' : 'hpJogadorTexto',
      defensor.hp,
      defensor.hpMax
    );

    let log = `${atacante.nome} usou ${golpe.nome}!`;
    if (res.dano > 0) log += ` Causou ${res.dano} de dano.`;
    if (golpe.efeito && defensor.estagios[golpe.efeito] > -6) {
      defensor.estagios[golpe.efeito]--;
      log += ` ${golpe.efeito === 'ata' ? 'O Ataque' : 'A Defesa'} de ${defensor.nome} caiu!`;
    }
    if (res.critico) log += " Golpe crítico!";
    if (res.efec > 1) log += " É super efetivo!";
    else if (res.efec < 1) log += " Não é muito efetivo...";

    falar(log);
  }

  function executarTurno(golpeJogador) {
    const bicho = meuTime[vezDoBicho];
    const op = timeInimigo[vezDoInimigo];
    if (travado || bicho.hp <= 0 || op.hp <= 0) return;
    if (!golpeJogador.infinito && golpeJogador.ppAtual <= 0) return;

    travado = true;
    aplicarGolpe(bicho, op, golpeJogador, true);

    if (op.hp === 0) {
      setTimeout(() => {
        const prox = timeInimigo.findIndex(b => b.hp > 0);
        if (prox !== -1) {
          vezDoInimigo = prox;
          atualizarArena();
          falar(`${op.nome} desmaiou! O oponente enviou ${timeInimigo[prox].nome}!`);
          travado = false;
        } else {
          alert("Vitória esplêndida! Você derrotou todo o time oponente!");
          irParaMontagem();
        }
      }, 1300);
      return;
    }

    setTimeout(turnoBot, 1500);
  }

  function turnoBot() {
    const bicho = meuTime[vezDoBicho];
    const op = timeInimigo[vezDoInimigo];
    if (bicho.hp <= 0 || op.hp <= 0) return;

    // Bot também respeita os próprios PPs disponíveis
    const disponiveis = op.golpes.filter(g => g.ppAtual > 0);
    const golpeBot = disponiveis.length
      ? disponiveis[Math.floor(Math.random() * disponiveis.length)]
      : golpeCombate;

    aplicarGolpe(op, bicho, golpeBot, false);

    if (bicho.hp === 0) {
      setTimeout(() => {
        alert(`${bicho.nome} desmaiou!`);
        const prox = meuTime.findIndex(b => b.hp > 0);
        if (prox !== -1) {
          vezDoBicho = prox;
          atualizarArena();
          falar(`Vai, ${meuTime[prox].nome}!`);
          travado = false;
        } else {
          alert("Você não tem mais Pokémon disponíveis! Fim de jogo.");
          irParaMontagem();
        }
      }, 1200);
      return;
    }

    setTimeout(() => {
      falar("Sua vez de jogar!");
      travado = false;
    }, 1400);
  }

  function abrirTroca() {
    if (travado) return;

    const opcoes = meuTime
      .map((b, i) => (i !== vezDoBicho && b.hp > 0) ? `[${i + 1}] ${b.nome} (HP: ${Math.ceil(b.hp)}/${b.hpMax})` : null)
      .filter(Boolean);

    if (!opcoes.length) return falar("Nenhum outro Pokémon disponível para troca!");

    const idx = parseInt(prompt("Escolha um Pokémon para entrar:\n" + opcoes.join("\n"))) - 1;
    if (!meuTime[idx] || idx === vezDoBicho || meuTime[idx].hp <= 0) {
      return falar("Escolha inválida!");
    }

    travado = true;
    vezDoBicho = idx;
    atualizarArena();
    falar(`Vai, ${meuTime[vezDoBicho].nome}!`);
    setTimeout(turnoBot, 1400);
  }

  window.onload = () => {
    atualizarArena();
    falar(`Batalha iniciada! O oponente enviou ${timeInimigo[0].nome}! Vai, ${meuTime[0].nome}!`);
  };
</script>
</body>
</html>