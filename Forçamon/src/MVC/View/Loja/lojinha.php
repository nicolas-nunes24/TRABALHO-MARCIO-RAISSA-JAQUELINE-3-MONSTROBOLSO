<?php
  $raiz = str_replace('\\', '/', dirname(__DIR__, 4));
  $docRoot = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '/');
  $base = str_ireplace($docRoot, '', $raiz);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lojinha - Forçamon</title>
  <link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">
  <style>
- { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Nunito', sans-serif;
      font-weight: 700;
      color: #fff;
      min-height: 100vh;
      background-color: #0a1330;
      background-image: linear-gradient(rgba(6, 11, 28, 0.85), rgba(6, 11, 28, 0.92)), url('<?= $base ?>/imagens_cenarios/bg-pokemon.jpg');
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
      filter: drop-shadow(0 3px 0 rgba(0,0,0,.35));
    }
    .logo svg { width: 46px; height: 46px; margin: 0 3px 0 2px; }

    .topo-dir { display: flex; align-items: center; gap: 14px; }
    .saldo {
      font-family: 'Lilita One', sans-serif;
      font-size: 1.3rem;
      color: #ffcb05;
      background: #0a1330;
      border: 3px solid #ffcb05;
      border-radius: 10px;
      padding: 5px 16px 3px;
    }
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
    .btn-topo:active { transform: translateY(3px); box-shadow: 0 1px 0 #0a1a4d; }

    main { max-width: 1100px; margin: 0 auto; padding: 56px 24px 60px; }

    .painel {
      position: relative;
      background: rgba(6, 12, 32, 0.85);
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

    .grade { display: grid; grid-template-columns: repeat(auto-fill, minmax(210px, 1fr)); gap: 20px; }
    .item {
      background: #eef1f6;
      border: 3px solid #fff;
      border-radius: 14px;
      overflow: hidden;
      display: flex;
      flex-direction: column;
    }
    .item:hover { border-color: #ffcb05; }
    .icone { height: 140px; display: flex; align-items: center; justify-content: center; }
    .icone img { width: 96px; height: 96px; image-rendering: pixelated; }
    .info { background: #12285a; border-top: 3px solid #ffcb05; padding: 10px 12px 12px; flex: 1; display: flex; flex-direction: column; gap: 6px; }
    .linha { display: flex; justify-content: space-between; align-items: center; gap: 8px; }
    .nome { font-weight: 800; font-size: 1.15rem; }
    .preco { font-family: 'Lilita One', sans-serif; font-size: .95rem; background: #ffcb05; color: #12285a; padding: 1px 9px; border-radius: 6px; white-space: nowrap; }
    .efeito { font-size: .88rem; color: #c9d3f2; min-height: 2.4em; line-height: 1.2; }
    .qtd { font-size: .85rem; color: #aebbe3; }

    .btn-comprar {
      background: #ffcb05;
      color: #12285a;
      border: none;
      font-family: 'Lilita One', sans-serif;
      font-size: 1.05rem;
      padding: 6px 20px;
      border-radius: 8px;
      box-shadow: 0 4px 0 #c79a00;
      cursor: pointer;
    }
    .btn-comprar:active { transform: translateY(3px); box-shadow: 0 1px 0 #c79a00; }
    .btn-comprar.travado { filter: grayscale(.85) brightness(.7); }

    .msg-carregando { text-align: center; font-family: 'Lilita One', sans-serif; font-size: 1.3rem; color: #8d9cc7; padding: 40px; }

    #aviso {
      position: fixed;
      left: 50%;
      bottom: 28px;
      transform: translateX(-50%) translateY(20px);
      background: #12285a;
      border: 3px solid #fff;
      border-bottom: 4px solid #ffcb05;
      border-radius: 12px;
      padding: 10px 24px;
      font-weight: 800;
      opacity: 0;
      pointer-events: none;
      transition: opacity .2s, transform .2s;
      z-index: 50;
    }
    #aviso.mostrar { opacity: 1; transform: translateX(-50%); }
    #aviso.erro { border-color: #ff6b6b; border-bottom-color: #ff6b6b; }

    @media (max-width: 760px) {
      .topo { padding: 10px 16px; }
      .logo { font-size: 2rem; -webkit-text-stroke-width: 4px; }
      .logo svg { width: 30px; height: 30px; }
      .btn-topo { padding: 8px 14px; font-size: .95rem; }
      .saldo { font-size: 1.05rem; padding: 4px 10px 2px; }
      .grade { grid-template-columns: repeat(2, 1fr); gap: 12px; }
      .aba { font-size: 1.4rem; }
    }
  </style>
</head>
<body>

  <header class="topo">
    <a class="logo" href="<?= $base ?>/site_jogo.php"><span>FORÇAM</span><svg viewBox="0 0 48 48"><circle cx="24" cy="24" r="21" fill="#3563b8"/><circle cx="24" cy="24" r="17" fill="#fff" stroke="#1a2a55" stroke-width="3"/><path d="M7 24a17 17 0 0 1 34 0z" fill="#e8312f" stroke="#1a2a55" stroke-width="3" stroke-linejoin="round"/><line x1="7" y1="24" x2="41" y2="24" stroke="#1a2a55" stroke-width="3.5"/><circle cx="24" cy="24" r="6" fill="#fff" stroke="#1a2a55" stroke-width="3"/></svg><span>N</span></a>
    <div class="topo-dir">
      <div class="saldo" id="saldo">₽ 0</div>
      <a class="btn-topo" href="<?= $base ?>/site_jogo.php">Menu</a>
    </div>
  </header>

  <main id="conteudo">
    <div class="msg-carregando">Carregando...</div>
  </main>

  <div id="aviso"></div>

  <script>
    const CATEGORIAS = [
      { id: 'hp',       titulo: 'Poções de HP' },
      { id: 'status',   titulo: 'Poções de Cura' },
      { id: 'reviver',  titulo: 'Poção de reviver' },
      { id: 'evolucao', titulo: 'Pedra Preciosa' }
    ];

    let moeda = 0;
    let itens = [];
    let timerAviso;

    /**
     * Recebe um texto (t).
     * Escapa caracteres especiais HTML usando um elemento temporário e retorna a string segura para inserção no DOM.
     */
    function esc(t) {
      const d = document.createElement('div');
      d.textContent = t;
      return d.innerHTML;
    }

    /**
     * Recebe um valor numérico (v).
     * Formata o número no padrão brasileiro precedido pelo símbolo '₽ ' e retorna a string formatada.
     */
    function dinheiro(v) {
      return '₽ ' + Number(v).toLocaleString('pt-BR');
    }

    /**
     * Recebe o texto da mensagem (texto) e opcionalmente um booleano indicando erro (erro, padrão false).
     * Exibe o banner de aviso na tela com estilo normal ou de erro e agenda seu ocultamento após 2,6 segundos.
     */
    function aviso(texto, erro = false) {
      const el = document.getElementById('aviso');
      el.textContent = texto;
      el.className = 'mostrar' + (erro ? ' erro' : '');
      clearTimeout(timerAviso);
      timerAviso = setTimeout(() => el.className = '', 2600);
    }

    /**
     * Recebe um objeto de item (item).
     * Consulta a PokéAPI pelo slug do item para obter a URL do sprite e a salva em item.sprite, usando uma URL de reserva caso a requisição falhe.
     */
    async function buscarSprite(item) {
      const reserva = `https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/items/${item.slug}.png`;
      try {
        const resp = await fetch(`https://pokeapi.co/api/v2/item/${item.slug}`);
        const dados = await resp.json();
        item.sprite = dados.sprites.default || reserva;
      } catch (e) {
        item.sprite = reserva;
      }
    }

    /**
     * Atualiza o saldo exibido no topo e renderiza no elemento principal os painéis de categorias com os cartões de itens, preços, quantidades na mochila e botões de compra.
     */
    function desenhar() {
      document.getElementById('saldo').textContent = dinheiro(moeda);

      let html = '';
      CATEGORIAS.forEach(cat => {
        const lista = itens.filter(i => i.categoria === cat.id);
        if (!lista.length) return;
        html += `<section class="painel"><div class="aba">${cat.titulo}</div><div class="grade">`;
        lista.forEach(i => {
          html += `
            <div class="item">
              <div class="icone"><img src="${esc(i.sprite)}" alt="${esc(i.nome)}"></div>
              <div class="info">
                <div class="linha"><span class="nome">${esc(i.nome)}</span><span class="preco">${dinheiro(i.preco)}</span></div>
                <div class="efeito">${esc(i.efeito)}</div>
                <div class="linha">
                  <span class="qtd">Mochila: ${i.qtd}</span>
                  <button class="btn-comprar ${moeda < i.preco ? 'travado' : ''}" onclick="comprar(${i.id})">Comprar</button>
                </div>
              </div>
            </div>`;
        });
        html += `</div></section>`;
      });
      document.getElementById('conteudo').innerHTML = html;
    }

    /**
     * Busca o saldo e a lista de itens da loja na API, carrega os sprites de todos os itens em paralelo e chama desenhar() para renderizar a página (ou exibe mensagem de erro em caso de falha).
     */
    async function carregar() {
      try {
        const resp = await fetch('<?= $base ?>/api_loja.php?acao=listar');
        const dados = await resp.json();
        if (!dados.ok) {
          document.getElementById('conteudo').innerHTML = `<div class="msg-carregando">${esc(dados.mensagem)}</div>`;
          return;
        }
        moeda = dados.moeda;
        itens = dados.itens;
        await Promise.all(itens.map(buscarSprite));
        desenhar();
      } catch (e) {
        document.getElementById('conteudo').innerHTML = '<div class="msg-carregando">Não foi possível carregar a loja.</div>';
      }
    }

    /**
     * Recebe o ID do item (id).
     * Verifica se há saldo suficiente, envia uma requisição POST para efetuar a compra de 1 unidade na API, atualiza o saldo e a quantidade do item na tela e exibe um aviso com o resultado.
     */
    async function comprar(id) {
      const item = itens.find(i => i.id === id);
      if (item && moeda < item.preco) {
        aviso('Saldo insuficiente.', true);
        return;
      }
      try {
        const resp = await fetch('<?= $base ?>/api_loja.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ acao: 'comprar', item_id: id, quantidade: 1 })
        });
        const dados = await resp.json();
        if (!dados.ok) {
          aviso(dados.mensagem, true);
          return;
        }
        moeda = dados.moeda;
        item.qtd = dados.qtd;
        desenhar();
        aviso(dados.mensagem);
      } catch (e) {
        aviso('Erro ao comprar. Tente de novo.', true);
      }
    }

    carregar();
  </script>
</body>
</html>