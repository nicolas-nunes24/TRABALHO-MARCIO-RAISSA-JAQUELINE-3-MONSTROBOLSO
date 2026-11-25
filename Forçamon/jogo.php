<?php
session_start();

if (empty($_SESSION['meu_time'])) {
    header("Location: monte_time.php");
    exit;
}

require_once __DIR__ . '/classes/autoload.php';

// Rubizard só existe como evolução do Dragofav, então não entra como forçamon inicial.
$soPorEvolucao = CatalogoForcamon::nomesSoPorEvolucao();
$meuTime = array_values(array_filter(
    $_SESSION['meu_time'],
    fn($p) => !in_array($p['nome'] ?? '', $soPorEvolucao, true)
));

if (count($meuTime) < 3) {
    header("Location: monte_time.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Arena - Forçamon</title>
  <link rel="stylesheet" href="CSS/arenaJogo.css?v=9">
</head>
<body>

  <header class="topo-arena">
    <a class="logo-arena" href="site_jogo.php">
      <span>FORÇAM</span>
      <svg viewBox="0 0 48 48"><circle cx="24" cy="24" r="21" fill="#3563b8"/><circle cx="24" cy="24" r="17" fill="#fff" stroke="#1a2a55" stroke-width="3"/><path d="M7 24a17 17 0 0 1 34 0z" fill="#e8312f" stroke="#1a2a55" stroke-width="3" stroke-linejoin="round"/><line x1="7" y1="24" x2="41" y2="24" stroke="#1a2a55" stroke-width="3.5"/><circle cx="24" cy="24" r="6" fill="#fff" stroke="#1a2a55" stroke-width="3"/></svg>
      <span>N</span>
    </a>
    <div class="botoes-topo">
      <button class="voltar-menu" onclick="irParaMontagem()">Mudar Time</button>
      <button class="voltar-menu" onclick="window.location.href='site_jogo.php'">Menu</button>
    </div>
  </header>

  <div class="arena-caixa">
    <div class="cenario">
      <div class="caixa-status status-inimigo">
        <div class="topo-status">
          <span class="nome-bicho" id="nomeInimigo">Inimigo</span>
          <span class="texto-hp" id="hpInimigoTexto">100/100</span>
        </div>
        <div class="fundo-barra">
          <div class="cheia-barra" id="barraInimigo"></div>
        </div>
      </div>

      <div class="boneco boneco-inimigo" id="boxInimigo">
        <img id="imgInimigo" src="" alt="Inimigo">
      </div>

      <div class="boneco boneco-jogador" id="boxJogador">
        <img id="imgJogador" src="" alt="Jogador">
      </div>

      <div class="caixa-status status-jogador">
        <div class="topo-status">
          <span class="nome-bicho" id="nomeJogador">Forçamon</span>
          <span class="texto-hp" id="hpJogadorTexto">100/100</span>
        </div>
        <div class="fundo-barra">
          <div class="cheia-barra" id="barraJogador"></div>
        </div>
      </div>
    </div>

    <div class="painel-baixo">
      <div class="caixa-texto" id="falaDoJogo">Preparando arena...</div>
      
      <div class="menu-botoes">
        <button class="botao-acao" id="btnAtaque1">Investida</button>
        <button class="botao-acao" id="btnAtaque2">Elemental</button>
        <button class="botao-acao" id="btnAtaque3">Debuff</button>
        <button class="botao-acao" onclick="abrirTroca()">Trocar Time</button>
        <button class="botao-acao" onclick="abrirMochila()">Mochila</button>
        <button class="botao-acao btn-fugir" onclick="fugirBatalha()">Fugir</button>
      </div>
    </div>
  </div>

  <div class="modal-arena" id="modalArena">
    <div class="modal-box">
      <div class="modal-titulo" id="modalTitulo">Escolher</div>
      <div class="modal-lista" id="modalLista"></div>
      <button class="btn-fechar-modal" onclick="fecharModal()">Cancelar</button>
    </div>
  </div>

  <script>
    
    const enviarLog = (categoria, mensagem) => {
  fetch('api_log.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ categoria, mensagem })
  });
};

  const $ = id => document.getElementById(id);
  const falar = txt => $('falaDoJogo').innerText = txt;
  const irParaMontagem = () => window.location.href = "monte_time.php";
  const multEstagio = e => e >= 0 ? (2 + e) / 2 : 2 / (2 - e);

  const cacheSprites = {};
  function carregarSpriteTransparente(imgEl, url, reserva) {
    if (!url) return;
    if (cacheSprites[url]) {
      imgEl.src = cacheSprites[url];
      return;
    }

    const temp = new Image();
    temp.crossOrigin = "anonymous";
    temp.onload = () => {
      try {
        const w = temp.naturalWidth;
        const h = temp.naturalHeight;
        const canvas = document.createElement('canvas');
        canvas.width = w;
        canvas.height = h;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(temp, 0, 0);

        const imgData = ctx.getImageData(0, 0, w, h);
        const d = imgData.data;
        const visitado = new Uint8Array(w * h);
        const fila = new Int32Array(w * h);
        let ini = 0, fim = 0;

        const ehFundo = idx => {
          const p = idx * 4;
          const r = d[p], g = d[p + 1], b = d[p + 2], a = d[p + 3];
          if (a < 25) return true;
          const min = Math.min(r, g, b);
          const max = Math.max(r, g, b);
          return min > 210 && (max - min) < 28;
        };

        const enfileirar = (x, y) => {
          if (x < 0 || y < 0 || x >= w || y >= h) return;
          const idx = y * w + x;
          if (!visitado[idx] && ehFundo(idx)) {
            visitado[idx] = 1;
            fila[fim++] = idx;
          }
        };

        for (let x = 0; x < w; x++) {
          enfileirar(x, 0);
          enfileirar(x, h - 1);
        }
        for (let y = 0; y < h; y++) {
          enfileirar(0, y);
          enfileirar(w - 1, y);
        }

        while (ini < fim) {
          const idx = fila[ini++];
          d[idx * 4 + 3] = 0;
          const x = idx % w;
          const y = (idx - x) / w;
          enfileirar(x + 1, y);
          enfileirar(x - 1, y);
          enfileirar(x, y + 1);
          enfileirar(x, y - 1);
        }

        // Suaviza a auréola branca de 1px que encosta no fundo apagado
        for (let y = 1; y < h - 1; y++) {
          for (let x = 1; x < w - 1; x++) {
            const idx = y * w + x;
            if (visitado[idx]) continue;
            if (visitado[idx - 1] || visitado[idx + 1] || visitado[idx - w] || visitado[idx + w]) {
              const p = idx * 4;
              const brilho = (d[p] + d[p + 1] + d[p + 2]) / 3;
              if (brilho > 180) {
                d[p + 3] = Math.round(d[p + 3] * 0.25);
              }
            }
          }
        }

        ctx.putImageData(imgData, 0, 0);
        const limpo = canvas.toDataURL('image/png');
        cacheSprites[url] = limpo;
        imgEl.src = limpo;
      } catch (e) {
        imgEl.src = url;
      }
    };
    temp.onerror = () => {
      if (reserva && reserva !== url) {
        carregarSpriteTransparente(imgEl, reserva);
      } else {
        imgEl.src = url;
      }
    };
    temp.src = url;
  }

  const vantagens = {
    fogo: { fogo: 0.5, agua: 0.5, planta: 2, normal: 1, ice: 2, bug: 2, steel: 2 },
    agua: { fogo: 2, agua: 0.5, planta: 0.5, normal: 1, rock: 2, ground: 2 },
    planta: { fogo: 0.5, agua: 2, planta: 0.5, normal: 1, rock: 2, ground: 2 },
    psiquico: { luta: 2, poison: 2, psiquico: 0.5, dark: 0.5, steel: 0.5 },
    normal: { fogo: 1, agua: 1, planta: 1, normal: 1 },
    ice: { fogo: 0.5, agua: 0.5, planta: 2, dragao: 2, flying: 2, ground: 2 },
    dark: { psiquico: 2, ghost: 2, dark: 0.5, luta: 0.5 },
    bug: { planta: 2, psiquico: 2, dark: 2, fogo: 0.5 },
    dragao: { dragao: 2, steel: 0.5 },
    steel: { rock: 2, ice: 2, fada: 2, steel: 0.5, fogo: 0.5, agua: 0.5 }
  };

  const golpeCombate = { nome: "Combate", poder: 50, precisao: 100, tipo: "normal", infinito: true };

  const normalizarPoke = p => {
    const golpesBase = p.golpes || [
      { nome: "Investida", poder: 40, precisao: 100, tipo: "normal", pp: 35 }
    ];

    return {
      ...p,
      nivel: p.nivel || 50,
      tipo: p.tipo || "normal",
      ataque: p.ataque || 50,
      defesa: p.defesa || 50,
      velocidade: p.velocidade || 50,
      hpMax: p.hpMax || 100,
      hp: p.hp ?? p.hpMax ?? 100,
      status: p.status || 'Saudavel',
      fraquezas: p.fraquezas || [],
      evolucao: p.evolucao || null,
      imgBase: p.imgBase || p.img,
      evoluido: p.evoluido || false,
      estagios: { ata: 0, def: 0 },
      golpes: golpesBase.map(g => ({
        ...g,
        ppMax: g.pp,
        ppAtual: g.pp
      }))
    };
  };

  function sortearInimigos(lista, qtd = 3) {
    const copia = JSON.parse(JSON.stringify(lista));
    for (let i = copia.length - 1; i > 0; i--) {
      const j = Math.floor(Math.random() * (i + 1));
      [copia[i], copia[j]] = [copia[j], copia[i]];
    }
    return copia.slice(0, qtd).map(normalizarPoke);
  }

  let meuTimeSessao = <?php echo json_encode($meuTime); ?>;
  let meuTime = [];
  let timeInimigo = [];
  let vezDoBicho = 0;
  let vezDoInimigo = 0;
  let turnos = 0;
  let travado = true;

  function animar(idElemento, classeAnim, duracao = 950) {
    const el = $(idElemento);
    if (!el) return;
    el.classList.remove(classeAnim);
    void el.offsetWidth;
    el.classList.add(classeAnim);
    setTimeout(() => el.classList.remove(classeAnim), duracao);
  }

  function atualizarBarraHP(idBarra, idTexto, hp, hpMax) {
    const porc = Math.max(0, (hp / hpMax) * 100);
    const barra = $(idBarra);
    barra.style.width = porc + '%';
    barra.style.backgroundColor = porc > 50 ? '#22c55e' : porc > 20 ? '#eab308' : '#ef4444';
    $(idTexto).innerText = `${Math.ceil(hp)}/${hpMax}`;
  }

  function atualizarArena() {
    const bicho = meuTime[vezDoBicho];
    const op = timeInimigo[vezDoInimigo];

    $('nomeJogador').innerText = bicho.nome;
    carregarSpriteTransparente($('imgJogador'), bicho.img, bicho.imgBase);
    atualizarBarraHP('barraJogador', 'hpJogadorTexto', bicho.hp, bicho.hpMax);

    $('nomeInimigo').innerText = op.nome;
    carregarSpriteTransparente($('imgInimigo'), op.img, op.imgBase);
    atualizarBarraHP('barraInimigo', 'hpInimigoTexto', op.hp, op.hpMax);

    const botoes = [$('btnAtaque1'), $('btnAtaque2'), $('btnAtaque3')];
    const semPP = bicho.golpes.every(g => g.ppAtual <= 0);

    if (semPP) {
      botoes[0].innerText = "Combate [∞]";
      botoes[0].disabled = false;
      botoes[0].onclick = () => executarTurno(golpeCombate);

      botoes.slice(1).forEach(btn => {
        btn.innerText = "---";
        btn.disabled = true;
      });
      return;
    }

    bicho.golpes.forEach((golpe, i) => {
      const btn = botoes[i];
      if (!btn) return;
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

  function aplicarGolpe(atacante, defensor, golpe, alvoEhInimigo) {
    if (!golpe.infinito && golpe.ppAtual > 0) {
      golpe.ppAtual--;
      atualizarArena();
    }

    animar(alvoEhInimigo ? 'boxJogador' : 'boxInimigo', alvoEhInimigo ? 'anim-ataque-jogador' : 'anim-ataque-inimigo');

    const res = calcularDano(atacante, defensor, golpe);
    if (res.errou) {
      falar(`${atacante.nome} usou ${golpe.nome}, mas errou!`);
      return;
    }

    if (res.dano > 0) {
      setTimeout(() => animar(alvoEhInimigo ? 'boxInimigo' : 'boxJogador', 'anim-dano'), 160);
    }

    defensor.hp = Math.max(0, defensor.hp - res.dano);
    atualizarBarraHP(
      alvoEhInimigo ? 'barraInimigo' : 'barraJogador',
      alvoEhInimigo ? 'hpInimigoTexto' : 'hpJogadorTexto',
      defensor.hp,
      defensor.hpMax
    );

    let log = `${atacante.nome} usou ${golpe.nome}!`;
    if (res.dano > 0) log += ` (-${res.dano} HP)`;
    if (golpe.efeito && defensor.estagios[golpe.efeito] > -6) {
      defensor.estagios[golpe.efeito]--;
      log += ` ${golpe.efeito === 'ata' ? 'Ataque' : 'Defesa'} de ${defensor.nome} caiu!`;
    }
    if (res.critico) log += " Crítico!";
    if (res.efec > 1) log += " Super efetivo!";
    else if (res.efec < 1) log += " Pouco efetivo...";

    falar(log);
  }

  async function encerrarBatalha(resultado, msgFinal) {
    try {
      const res = await fetch('api_batalha.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          acao: 'finalizar',
          time_jogador: meuTime.map(p => p.nome),
          time_oponente: timeInimigo.map(p => p.nome),
          resultado,
          turnos
        })
      });
      const dados = await res.json();
      if (dados.ok && dados.recompensa > 0) {
        msgFinal += `\nPrêmio: ₽ ${dados.recompensa} (Saldo: ₽ ${dados.moedas})`;
      }
    } catch (e) {
      console.error(e);
    }
    alert(msgFinal);
    irParaMontagem();
  }

  function fugirBatalha() {
    if (travado) return;
    travado = true;
    encerrarBatalha('fuga', 'Você fugiu da arena!');
  }

  function escolherGolpeBot(op) {
    const disponiveis = op.golpes.filter(g => g.ppAtual > 0);
    return disponiveis.length
      ? disponiveis[Math.floor(Math.random() * disponiveis.length)]
      : golpeCombate;
  }

  function executarTurno(golpeJogador) {
    const bicho = meuTime[vezDoBicho];
    const op = timeInimigo[vezDoInimigo];
    if (travado || bicho.hp <= 0 || op.hp <= 0) return;
    if (!golpeJogador.infinito && golpeJogador.ppAtual <= 0) return;

    travado = true;
    turnos++;

    const jogadorMaisRapido = bicho.velocidade >= op.velocidade;

    if (jogadorMaisRapido) {
      aplicarGolpe(bicho, op, golpeJogador, true);
      if (op.hp === 0) return checarDesmaioInimigo(op);
      setTimeout(() => {
        aplicarGolpe(op, bicho, escolherGolpeBot(op), false);
        checarDesmaioJogador(bicho);
      }, 1400);
    } else {
      aplicarGolpe(op, bicho, escolherGolpeBot(op), false);
      if (bicho.hp === 0) return checarDesmaioJogador(bicho);
      setTimeout(() => {
        aplicarGolpe(bicho, op, golpeJogador, true);
        checarDesmaioInimigo(op);
      }, 1400);
    }
  }

  function checarDesmaioInimigo(op) {
    if (op.hp > 0) {
      setTimeout(() => {
        falar(`O que ${meuTime[vezDoBicho].nome} vai fazer?`);
        travado = false;
      }, 1200);
      return;
    }

    setTimeout(() => {
      const prox = timeInimigo.findIndex(b => b.hp > 0);
      if (prox !== -1) {
        vezDoInimigo = prox;
        atualizarArena();
        falar(`${op.nome} caiu! O rival mandou ${timeInimigo[prox].nome}!`);
        travado = false;
      } else {
        encerrarBatalha('vitoria', 'Vitória! Você derrubou todo o time rival.');
      }
    }, 1200);
  }

  function checarDesmaioJogador(bicho) {
    if (bicho.hp > 0) {
      setTimeout(() => {
        falar(`O que ${bicho.nome} vai fazer?`);
        travado = false;
      }, 1200);
      return;
    }

    setTimeout(() => {
      const prox = meuTime.findIndex(b => b.hp > 0);
      if (prox !== -1) {
        vezDoBicho = prox;
        atualizarArena();
        falar(`${bicho.nome} caiu! Vai, ${meuTime[prox].nome}!`);
        travado = false;
      } else {
        encerrarBatalha('derrota', 'Derrota! Todo o seu time desmaiou.');
      }
    }, 1200);
  }

  function fecharModal() {
    $('modalArena').classList.remove('ativo');
  }

  // Copia os dados da forma evoluída (vindos do servidor) pro bicho que está em jogo,
  // mantendo PP dos golpes e estágios de ataque/defesa.
  function aplicarForma(bicho, forma) {
    bicho.nome = forma.nome;
    bicho.img = forma.img;
    bicho.hpMax = forma.hpMax;
    bicho.hp = forma.hp;
    bicho.ataque = forma.ataque;
    bicho.defesa = forma.defesa;
    bicho.velocidade = forma.velocidade;
    bicho.evoluido = true;
  }

  async function evoluirAtivo(selecionado) {
    const bicho = meuTime[vezDoBicho];

    if (bicho.evoluido) return falar(`${bicho.nome} já evoluiu!`);
    if (!bicho.evolucao) return falar(`${bicho.nome} não tem evolução. A pedra não foi gasta.`);

    const res = await fetch('api_batalha.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ acao: 'evoluir', forcamon_id: bicho.id, item_id: selecionado.id_item })
    });
    const dados = await res.json();
    if (!dados.ok) return falar(dados.mensagem);

    const nomeAntigo = bicho.nome;
    travado = true;
    falar(`O quê? ${nomeAntigo} está evoluindo!`);
    animar('boxJogador', 'anim-evolucao', 1700);

    // troca a forma no ponto mais claro da animação
    setTimeout(() => {
      aplicarForma(bicho, dados.forma);
      atualizarArena();
    }, 800);

    setTimeout(() => {
      falar(`${nomeAntigo} evoluiu para ${bicho.nome}!`);
      enviarLog('itens', `${nomeAntigo} evoluiu para ${bicho.nome}.`);
    }, 1700);

    setTimeout(() => {
      const op = timeInimigo[vezDoInimigo];
      aplicarGolpe(op, bicho, escolherGolpeBot(op), false);
      checarDesmaioJogador(bicho);
    }, 3300);
  }

  function abrirTroca() {
    if (travado) return;

    const disponiveis = meuTime
      .map((b, i) => ({ b, i }))
      .filter(({ b, i }) => i !== vezDoBicho && b.hp > 0);

    if (!disponiveis.length) return falar("Nenhum reserva disponível!");

    $('modalTitulo').innerText = "Trocar Forçamon";
    $('modalLista').innerHTML = disponiveis.map(({ b, i }) => `
      <button class="item-opcao" onclick="confirmarTroca(${i})">
        <span>${b.nome}</span>
        <span>HP ${Math.ceil(b.hp)}/${b.hpMax}</span>
      </button>
    `).join('');
    $('modalArena').classList.add('ativo');
  }

  function confirmarTroca(idx) {
    fecharModal();
    if (!meuTime[idx] || idx === vezDoBicho || meuTime[idx].hp <= 0) return;

    travado = true;
    vezDoBicho = idx;
    atualizarArena();
    falar(`Vai, ${meuTime[vezDoBicho].nome}!`);

    setTimeout(() => {
      const bicho = meuTime[vezDoBicho];
      const op = timeInimigo[vezDoInimigo];
      aplicarGolpe(op, bicho, escolherGolpeBot(op), false);
      checarDesmaioJogador(bicho);
    }, 1300);
  }

  async function abrirMochila() {
    if (travado) return;
    try {
      const res = await fetch('api_batalha.php?acao=mochila');
      const dados = await res.json();
      if (!dados.ok || !dados.itens.length) {
        return falar("Mochila vazia! Compre itens na Lojinha.");
      }

      $('modalTitulo').innerText = "Mochila";
      $('modalLista').innerHTML = dados.itens.map(it => `
        <button class="item-opcao" onclick='usarItemMochila(${JSON.stringify(it)})'>
          <span>${it.nomeItem}</span>
          <span>x${it.quantidade}</span>
        </button>
      `).join('');
      $('modalArena').classList.add('ativo');
    } catch (e) {
      falar("Erro ao abrir a mochila.");
    }
  }

  async function usarItemMochila(selecionado) {
    fecharModal();
    try {
      if (selecionado.tipoItem === 'evolucao') {
        return await evoluirAtivo(selecionado);
      }
      if (selecionado.tipoItem === 'reviver' && !meuTime.some(p => p.hp <= 0)) {
        return falar("Ninguém desmaiou ainda. O item não foi gasto.");
      }

      const usoRes = await fetch('api_batalha.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ acao: 'usar_item', item_id: selecionado.id_item })
      });
      const uso = await usoRes.json();
      if (!uso.ok) return falar(uso.mensagem);

      const bicho = meuTime[vezDoBicho];
      let msgUso = `${selecionado.nomeItem} usado em ${bicho.nome}!`;

      if (selecionado.tipoItem === 'hp') {
        const cura = selecionado.valorEfeito ? Number(selecionado.valorEfeito) : bicho.hpMax;
        bicho.hp = Math.min(bicho.hpMax, bicho.hp + cura);
      } else if (selecionado.tipoItem === 'reviver') {
        const caido = meuTime.find(p => p.hp <= 0);
        if (caido) {
          caido.hp = Math.floor(caido.hpMax / 2);
          msgUso = `${caido.nome} levantou de volta pra luta!`;
        }
      }

      if (selecionado.statusCura) {
        bicho.status = 'Saudavel';
      }

      travado = true;
      atualizarArena();
      falar(msgUso);

      setTimeout(() => {
        const op = timeInimigo[vezDoInimigo];
        aplicarGolpe(op, bicho, escolherGolpeBot(op), false);
        checarDesmaioJogador(bicho);
      }, 1500);
    } catch (e) {
      falar("Erro ao usar o item.");
    }
  }

  async function aplicarEvolucoesSalvas() {
    try {
      const res = await fetch('api_batalha.php?acao=evolucoes');
      const dados = await res.json();
      if (!dados.ok) return;
      dados.formas.forEach(forma => {
        const bicho = meuTime.find(p => p.id === forma.id);
        if (bicho) aplicarForma(bicho, forma);
      });
    } catch (e) {
      console.error(e);
    }
  }

  window.onload = async () => {
    try {
      const res = await fetch('api_pokemons.php');
      const dados = await res.json();
      const catalogo = dados.resultados;

      meuTime = meuTimeSessao.map(p => {
        const nomeLimpo = (p.nome || "").trim().toLowerCase();
        const oficial = catalogo.find(c => c.nome.trim().toLowerCase() === nomeLimpo);
        if (!oficial) return normalizarPoke(p);

        return normalizarPoke({
          ...p,
          ...oficial,
          hp: oficial.hpMax
        });
      });

      await aplicarEvolucoesSalvas();

      timeInimigo = sortearInimigos(catalogo, 3);
      travado = false;
      atualizarArena();
      falar(`Rival enviou ${timeInimigo[0].nome}! Vai, ${meuTime[0].nome}!`);
    } catch (erro) {
      falar("Erro ao carregar os Forçamons.");
    }
  };
  </script>
</body>
</html>