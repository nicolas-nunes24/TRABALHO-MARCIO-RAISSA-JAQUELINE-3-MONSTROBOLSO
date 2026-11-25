<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forçadex - Forçamon</title>
  <link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">
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
    .btn-topo:active { transform: translateY(3px); box-shadow: 0 1px 0 #0a1a4d; }

    main { max-width: 1100px; margin: 0 auto; padding: 56px 24px 60px; }

    /* painéis com abinha */
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

    /* busca e filtros */
    .linha-busca { display: flex; gap: 12px; }
    .linha-busca input {
      flex: 1;
      min-width: 0;
      padding: 11px 16px;
      border-radius: 10px;
      border: 3px solid #fff;
      background: #060b1c;
      color: #fff;
      font-family: 'Nunito', sans-serif;
      font-weight: 700;
      font-size: 1rem;
      outline: none;
    }
    .linha-busca input:focus { border-color: #ffcb05; }
    .linha-busca input::placeholder { color: #6a76a0; }

    .btn-filtro {
      background: #1d3a8a;
      color: #fff;
      border: 2px solid #fff;
      border-radius: 10px;
      box-shadow: 0 4px 0 #0a1a4d;
      font-family: 'Lilita One', sans-serif;
      font-size: 1.05rem;
      padding: 8px 18px;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 10px;
      white-space: nowrap;
    }
    .btn-filtro:active { transform: translateY(3px); box-shadow: 0 1px 0 #0a1a4d; }
    .btn-filtro .atual { color: #ffcb05; }
    .btn-filtro .seta { font-size: .7rem; }
    .btn-filtro.aberto .seta { transform: rotate(180deg); }

    .tags { display: none; flex-wrap: wrap; gap: 8px; margin-top: 18px; }
    .tags.show { display: flex; }
    .tag-btn {
      background: #12285a;
      border: 2px solid #fff;
      color: #fff;
      padding: 4px 14px;
      border-radius: 8px;
      font-family: 'Lilita One', sans-serif;
      font-size: .95rem;
      cursor: pointer;
    }
    .tag-btn:hover { border-color: #ffcb05; color: #ffcb05; }
    .tag-btn.active { background: #ffcb05; border-color: #ffcb05; color: #12285a; }

    /* cards */
    .grade { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; }
    .card-item {
      position: relative;
      background: #eef1f6;
      border: 3px solid #fff;
      border-radius: 14px;
      overflow: hidden;
      cursor: pointer;
      display: flex;
      flex-direction: column;
    }
    .card-item:hover { border-color: #ffcb05; }
    .card-thumb { width: 100%; aspect-ratio: 4 / 5; overflow: hidden; }
    .card-thumb img { width: 100%; height: 100%; object-fit: cover; object-position: top center; display: block; }
    .card-label {
      background: #12285a;
      border-top: 3px solid #ffcb05;
      padding: 8px 12px 10px;
    }
    .linha { display: flex; justify-content: space-between; align-items: baseline; gap: 8px; }
    .card-name { font-family: 'Nunito', sans-serif; font-weight: 800; font-size: 1.15rem; color: #fff; letter-spacing: .2px; }
    .card-num { font-weight: 700; font-size: .85rem; color: #aebbe3; }
    .tipos { display: flex; flex-wrap: wrap; gap: 4px; margin-top: 6px; }
    .evo-info { margin-top: 8px; padding: 4px 8px; font-size: .78rem; line-height: 1.3; color: #12285a; background: #ffcb05; border-radius: 8px; }
    .tipo {
      font-family: 'Nunito', sans-serif;
      font-weight: 800;
      font-size: .75rem;
      padding: 1px 8px;
      border-radius: 999px;
      border: 2px solid rgba(255,255,255,.85);
      color: #fff;
    }

    .fav-btn {
      position: absolute;
      top: 8px;
      right: 8px;
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: #12285a;
      border: 2px solid #fff;
      color: #8d9cc7;
      font-size: .95rem;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      z-index: 2;
    }
    .fav-btn:hover { border-color: #ffcb05; }
    .fav-btn.active { color: #ff4d4d; border-color: #ff4d4d; }

    .vazio { display: none; text-align: center; font-family: 'Lilita One', sans-serif; font-size: 1.3rem; color: #8d9cc7; padding: 20px; }

    /* visualizador */
    .modal {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(3, 6, 16, 0.92);
      z-index: 1000;
      justify-content: center;
      align-items: center;
    }
    .modal.active { display: flex; }
    .modal-content-wrapper { position: relative; width: 100%; height: 100%; display: flex; justify-content: center; align-items: center; }
    .modal-content { max-width: 85vw; max-height: 85vh; border: 3px solid #fff; border-radius: 12px; object-fit: contain; cursor: zoom-in; user-select: none; }

    .close-btn, .nav-arrow {
      position: absolute;
      background: #12285a;
      color: #fff;
      border: 3px solid #fff;
      border-bottom: 4px solid #ffcb05;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 12px;
      cursor: pointer;
      z-index: 1002;
    }
    .close-btn:hover, .nav-arrow:hover { color: #ffcb05; }
    .close-btn { top: 16px; right: 20px; width: 46px; height: 46px; font-size: 1.8rem; }
    .nav-arrow { top: 50%; transform: translateY(-50%); width: 46px; height: 56px; font-size: 1.3rem; }
    .prev-btn { left: 16px; }
    .next-btn { right: 16px; }

    .zoom-hint {
      position: absolute;
      bottom: 16px;
      background: #12285a;
      border: 2px solid #fff;
      color: #fff;
      font-size: .8rem;
      padding: 4px 14px;
      border-radius: 999px;
      pointer-events: none;
      z-index: 1001;
    }

    @media (max-width: 760px) {
      .topo { padding: 10px 16px; }
      .logo { font-size: 2rem; -webkit-text-stroke-width: 4px; }
      .logo svg { width: 30px; height: 30px; }
      .btn-topo { padding: 8px 14px; font-size: .95rem; }
      .linha-busca { flex-direction: column; }
      .btn-filtro { justify-content: space-between; }
      .grade { grid-template-columns: repeat(2, 1fr); gap: 12px; }
      .aba { font-size: 1.4rem; }
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
      <div class="aba">Forçadex</div>
      <div class="linha-busca">
        <input type="text" id="searchInput" placeholder="Pesquisar forçamon...">
        <button class="btn-filtro" id="filterToggleBtn" type="button">
          Filtros: <span class="atual" id="filterBadge">Todos</span> <span class="seta">▼</span>
        </button>
      </div>
      <div class="tags" id="filterTags">
        <button class="tag-btn active" data-filter="all">Todos</button>
        <button class="tag-btn" data-filter="bug">Inseto</button>
        <button class="tag-btn" data-filter="fire">Fogo</button>
        <button class="tag-btn" data-filter="poison">Veneno</button>
        <button class="tag-btn" data-filter="smoke">Fumaça</button>
        <button class="tag-btn" data-filter="manipulator">Manipulador</button>
        <button class="tag-btn" data-filter="honey">Mel</button>
        <button class="tag-btn" data-filter="dark">Dark</button>
        <button class="tag-btn" data-filter="water">Água</button>
        <button class="tag-btn" data-filter="psychic">Psíquico</button>
        <button class="tag-btn" data-filter="normal">Normal</button>
        <button class="tag-btn" data-filter="dragon">Dragão</button>
        <button class="tag-btn" data-filter="flying">Voador</button>
        <button class="tag-btn" data-filter="steel">Aço</button>
        <button class="tag-btn" data-filter="ice">Gelo</button>
        <button class="tag-btn" data-filter="fav">♥ Favoritos</button>
      </div>
    </section>

    <section class="painel">
      <div class="aba">Forçamons</div>
      <div class="contador" id="counterText">0/0</div>
      <div class="grade" id="forcamonGrid">
        <div class="card-item" data-name="nildrill" data-types="bug poison honey" data-fav="false">
          <button class="fav-btn" title="Favoritar">♥</button>
          <div class="card-thumb"><img src="personagens/Nildrill - forcamon.jpg" alt="Nildrill"></div>
          <div class="card-label">
            <div class="linha"><span class="card-name">Nildrill</span><span class="card-num">Nº 0015</span></div>
            <div class="tipos"><span class="tipo" style="background:#7f9418">Inseto</span><span class="tipo" style="background:#85479f">Veneno</span><span class="tipo" style="background:#b9861a">Mel</span></div>
          </div>
        </div>
        <div class="card-item" data-name="rubizard" data-types="fire" data-fav="false">
          <button class="fav-btn" title="Favoritar">♥</button>
          <div class="card-thumb"><img src="personagens/Rubizard - forcamon.jpg" alt="Rubizard"></div>
          <div class="card-label">
            <div class="linha"><span class="card-name">Rubizard</span><span class="card-num">Nº 0006</span></div>
            <div class="tipos"><span class="tipo" style="background:#c4491a">Fogo</span></div>
            <div class="evo-info">Evolução do <strong>Dragofav</strong></div>
          </div>
        </div>
        <div class="card-item" data-name="vianalax" data-types="normal" data-fav="false">
          <button class="fav-btn" title="Favoritar">♥</button>
          <div class="card-thumb"><img src="personagens/Vianalax - forcamon.jpg" alt="Vianalax"></div>
          <div class="card-label">
            <div class="linha"><span class="card-name">Vianalax</span><span class="card-num">Nº 0143</span></div>
            <div class="tipos"><span class="tipo" style="background:#6f727c">Normal</span></div>
          </div>
        </div>
        <div class="card-item" data-name="violetwo" data-types="psychic" data-fav="false">
          <button class="fav-btn" title="Favoritar">♥</button>
          <div class="card-thumb"><img src="personagens/Violetwo - forcamon.jpg" alt="Violetwo"></div>
          <div class="card-label">
            <div class="linha"><span class="card-name">Violetwo</span><span class="card-num">Nº 0150</span></div>
            <div class="tipos"><span class="tipo" style="background:#b83272">Psíquico</span></div>
          </div>
        </div>
        <div class="card-item" data-name="darkvi" data-types="dark" data-fav="false">
          <button class="fav-btn" title="Favoritar">♥</button>
          <div class="card-thumb"><img src="personagens/Darkvi - forcamon.jpg" alt="Darkvi"></div>
          <div class="card-label">
            <div class="linha"><span class="card-name">Darkvi</span><span class="card-num">Nº 0491</span></div>
            <div class="tipos"><span class="tipo" style="background:#4a3d38">Dark</span></div>
          </div>
        </div>
        <div class="card-item" data-name="brrpatana" data-types="water" data-fav="false">
          <button class="fav-btn" title="Favoritar">♥</button>
          <div class="card-thumb"><img src="personagens/Brrpatana - forcamon.jpg" alt="Brrpatana"></div>
          <div class="card-label">
            <div class="linha"><span class="card-name">Brrpatana</span><span class="card-num">Nº 0054</span></div>
            <div class="tipos"><span class="tipo" style="background:#2b72b8">Água</span></div>
          </div>
        </div>
        <div class="card-item" data-name="meloetta" data-types="normal psychic" data-fav="false">
          <button class="fav-btn" title="Favoritar">♥</button>
          <div class="card-thumb"><img src="personagens/Meloetta - forcamon.jpg" alt="Meloetta"></div>
          <div class="card-label">
            <div class="linha"><span class="card-name">Meloetta</span><span class="card-num">Nº 0648</span></div>
            <div class="tipos"><span class="tipo" style="background:#6f727c">Normal</span><span class="tipo" style="background:#b83272">Psíquico</span></div>
          </div>
        </div>
        <div class="card-item" data-name="dragofav" data-types="dragon flying" data-fav="false">
          <button class="fav-btn" title="Favoritar">♥</button>
          <div class="card-thumb"><img src="personagens/Dragofav - forcamon.jpg" alt="Dragofav"></div>
          <div class="card-label">
            <div class="linha"><span class="card-name">Dragofav</span><span class="card-num">Nº 0149</span></div>
            <div class="tipos"><span class="tipo" style="background:#503dba">Dragão</span><span class="tipo" style="background:#5f7fc0">Voador</span></div>
            <div class="evo-info">Evolui para <strong>Rubizard</strong> com a Pedra Preciosa</div>
          </div>
        </div>
        <div class="card-item" data-name="velostervroom" data-types="steel poison" data-fav="false">
          <button class="fav-btn" title="Favoritar">♥</button>
          <div class="card-thumb"><img src="personagens/Velostervroom - forcamon.jpg" alt="Velostervroom"></div>
          <div class="card-label">
            <div class="linha"><span class="card-name">Velostervroom</span><span class="card-num">Nº 0966</span></div>
            <div class="tipos"><span class="tipo" style="background:#56707f">Aço</span><span class="tipo" style="background:#85479f">Veneno</span></div>
          </div>
        </div>
        <div class="card-item" data-name="jynxtetoi" data-types="ice psychic" data-fav="false">
          <button class="fav-btn" title="Favoritar">♥</button>
          <div class="card-thumb"><img src="personagens/Jynxtetoi - forcamon.jpg" alt="Jynxtetoi"></div>
          <div class="card-label">
            <div class="linha"><span class="card-name">Jynxtetoi</span><span class="card-num">Nº 0124</span></div>
            <div class="tipos"><span class="tipo" style="background:#3fa0b4">Gelo</span><span class="tipo" style="background:#b83272">Psíquico</span></div>
          </div>
        </div>
      </div>
      <div class="vazio" id="vazio">Nenhum forçamon encontrado.</div>
    </section>
  </main>

  <div class="modal" id="imageModal">
    <span class="close-btn" id="closeBtn">&times;</span>
    <span class="nav-arrow prev-btn" id="prevBtn">&#10094;</span>
    <span class="nav-arrow next-btn" id="nextBtn">&#10095;</span>

    <div class="modal-content-wrapper" id="wrapper">
      <img class="modal-content" id="modalImg" alt="Card expandido" draggable="false">
    </div>

    <div class="zoom-hint">Scroll = zoom | Arraste = mover | Setas = navegar</div>
  </div>

  <script>
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImg');
    const closeBtn = document.getElementById('closeBtn');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const searchInput = document.getElementById('searchInput');
    const tagBtns = document.querySelectorAll('.tag-btn');
    const counterText = document.getElementById('counterText');
    const vazio = document.getElementById('vazio');
    const allCards = Array.from(document.querySelectorAll('.card-item'));

    const filterToggleBtn = document.getElementById('filterToggleBtn');
    const filterTags = document.getElementById('filterTags');
    const filterBadge = document.getElementById('filterBadge');

    let visibleCards = [...allCards];
    let currentIndex = 0;
    let currentFilter = 'all';

    let scale = 1;
    let isDragging = false;
    let startX = 0, startY = 0;
    let translateX = 0, translateY = 0;

    filterToggleBtn.addEventListener('click', () => {
      filterTags.classList.toggle('show');
      filterToggleBtn.classList.toggle('aberto');
    });

    allCards.forEach(card => {
      const favBtn = card.querySelector('.fav-btn');

      favBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        const isFav = card.dataset.fav === 'true';
        card.dataset.fav = !isFav;
        favBtn.classList.toggle('active', !isFav);
        applyFilters();
      });

      card.addEventListener('click', () => {
        const indexInVisible = visibleCards.indexOf(card);
        if (indexInVisible !== -1) {
          currentIndex = indexInVisible;
          loadCard(currentIndex);
          modal.classList.add('active');
        }
      });
    });

    function applyFilters() {
      const query = searchInput.value.toLowerCase().trim();

      visibleCards = allCards.filter(card => {
        const name = card.dataset.name.toLowerCase();
        const types = card.dataset.types.toLowerCase();
        const isFav = card.dataset.fav === 'true';

        const matchesSearch = name.includes(query);
        let matchesFilter = true;

        if (currentFilter === 'fav') {
          matchesFilter = isFav;
        } else if (currentFilter !== 'all') {
          matchesFilter = types.includes(currentFilter);
        }

        return matchesSearch && matchesFilter;
      });

      allCards.forEach(card => {
        card.style.display = visibleCards.includes(card) ? 'flex' : 'none';
      });

      counterText.textContent = `${visibleCards.length}/${allCards.length}`;
      vazio.style.display = visibleCards.length === 0 ? 'block' : 'none';
    }

    searchInput.addEventListener('input', applyFilters);

    tagBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        tagBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        currentFilter = btn.dataset.filter;
        filterBadge.textContent = btn.textContent.replace('♥ ', '');
        applyFilters();
      });
    });

    function loadCard(index) {
      if (visibleCards.length === 0) return;

      if (index < 0) index = visibleCards.length - 1;
      if (index >= visibleCards.length) index = 0;
      currentIndex = index;

      const img = visibleCards[currentIndex].querySelector('img');
      modalImg.src = img.src;
      modalImg.alt = img.alt;
      resetZoom();
    }

    function updateTransform() {
      modalImg.style.transform = `translate(${translateX}px, ${translateY}px) scale(${scale})`;
    }

    function updateCursor() {
      if (scale > 1) {
        modalImg.style.cursor = isDragging ? 'grabbing' : 'grab';
      } else {
        modalImg.style.cursor = 'zoom-in';
      }
    }

    modal.addEventListener('wheel', (e) => {
      e.preventDefault();

      if (e.deltaY < 0) {
        scale = Math.min(scale + 0.3, 4);
      } else {
        scale = Math.max(scale - 0.3, 1);
        if (scale === 1) {
          translateX = 0;
          translateY = 0;
        }
      }

      updateCursor();
      updateTransform();
    }, { passive: false });

    modalImg.addEventListener('mousedown', (e) => {
      if (scale > 1) {
        isDragging = true;
        startX = e.clientX - translateX;
        startY = e.clientY - translateY;
        updateCursor();
        e.preventDefault();
      }
    });

    window.addEventListener('mousemove', (e) => {
      if (!isDragging) return;
      translateX = e.clientX - startX;
      translateY = e.clientY - startY;
      updateTransform();
    });

    window.addEventListener('mouseup', () => {
      if (isDragging) {
        isDragging = false;
        updateCursor();
      }
    });

    prevBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      loadCard(currentIndex - 1);
    });

    nextBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      loadCard(currentIndex + 1);
    });

    closeBtn.addEventListener('click', closeModal);

    modal.addEventListener('click', (e) => {
      if ((e.target === modal || e.target.id === 'wrapper') && !isDragging) {
        closeModal();
      }
    });

    document.addEventListener('keydown', (e) => {
      if (!modal.classList.contains('active')) return;

      if (e.key === 'Escape') closeModal();
      if (e.key === 'ArrowLeft') loadCard(currentIndex - 1);
      if (e.key === 'ArrowRight') loadCard(currentIndex + 1);
    });

    function resetZoom() {
      scale = 1;
      translateX = 0;
      translateY = 0;
      isDragging = false;
      updateTransform();
      updateCursor();
    }

    function closeModal() {
      modal.classList.remove('active');
      resetZoom();
    }

    applyFilters();
  </script>

</body>
</html>