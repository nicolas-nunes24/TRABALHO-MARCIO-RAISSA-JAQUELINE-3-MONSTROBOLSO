-- Remove o banco de dados caso já exista, recria com suporte completo a acentos/emojis (utf8mb4) e o seleciona
DROP DATABASE IF EXISTS meu_jogo;
CREATE DATABASE meu_jogo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE meu_jogo;

-- Armazena os dados de acesso (login) dos usuários cadastrados no sistema
CREATE TABLE Usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    moedas INT NOT NULL DEFAULT 650
);

-- Representa o perfil de treinador vinculado a um usuário, guardando saldo de moedas e placar de batalhas
CREATE TABLE Perfil (
    id_perfil INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    nome_treinador VARCHAR(60) NOT NULL,
    moedas INT NOT NULL DEFAULT 650,
    vitorias INT NOT NULL DEFAULT 0,
    derrotas INT NOT NULL DEFAULT 0,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES Usuario(id_usuario) ON DELETE CASCADE
);

-- Catálogo com todos os Forçamons do jogo, seus atributos base de combate, fraquezas, imagem e evolução
CREATE TABLE forcamons (
    id INT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL UNIQUE,
    tipo VARCHAR(50) NOT NULL,
    nivel INT DEFAULT 50,
    hp_max INT NOT NULL,
    ataque INT NOT NULL,
    defesa INT NOT NULL,
    velocidade INT NOT NULL,
    fraquezas TEXT NOT NULL,
    img VARCHAR(255) NOT NULL,
    evolucao TEXT NULL
);

-- Catálogo de itens disponíveis para compra na loja (poções de HP, curas de status, reviver e pedras de evolução)
CREATE TABLE Loja (
    id_item INT AUTO_INCREMENT PRIMARY KEY,
    nomeItem VARCHAR(40) NOT NULL,
    tipoItem ENUM('hp', 'status', 'reviver', 'evolucao') NOT NULL,
    valorEfeito INT NULL,
    statusCura VARCHAR(20) NULL,
    valorItem INT NOT NULL,
    slugApi VARCHAR(40) NULL
);

-- Inventário de cada perfil, relacionando os itens comprados e suas respectivas quantidades
CREATE TABLE Mochila (
    id_perfil INT NOT NULL,
    id_item INT NOT NULL,
    quantidade INT NOT NULL DEFAULT 0,
    PRIMARY KEY (id_perfil, id_item),
    FOREIGN KEY (id_perfil) REFERENCES Perfil(id_perfil) ON DELETE CASCADE,
    FOREIGN KEY (id_item) REFERENCES Loja(id_item)
);

-- Histórico de todas as compras realizadas na loja pelos perfis
CREATE TABLE LogCompra (
    id_log INT AUTO_INCREMENT PRIMARY KEY,
    id_perfil INT NOT NULL,
    id_item INT NOT NULL,
    quantidade INT NOT NULL,
    valorTotal INT NOT NULL,
    dataCompra DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_perfil) REFERENCES Perfil(id_perfil) ON DELETE CASCADE,
    FOREIGN KEY (id_item) REFERENCES Loja(id_item)
);

-- Histórico de batalhas disputadas na arena, registrando times, resultado, turnos e recompensa
CREATE TABLE LogBatalha (
    id_batalha INT AUTO_INCREMENT PRIMARY KEY,
    id_perfil INT NOT NULL,
    time_jogador VARCHAR(255) NOT NULL,
    time_oponente VARCHAR(255) NOT NULL,
    resultado ENUM('vitoria', 'derrota', 'fuga') NOT NULL,
    turnos INT NOT NULL DEFAULT 1,
    moedas_ganhas INT NOT NULL DEFAULT 0,
    data_batalha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_perfil) REFERENCES Perfil(id_perfil) ON DELETE CASCADE
);

-- Registra quais Forçamons já foram evoluídos por cada perfil de treinador
CREATE TABLE ForcamonEvoluido (
    id_perfil INT NOT NULL,
    id_forcamon INT NOT NULL,
    evoluido_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_perfil, id_forcamon),
    FOREIGN KEY (id_perfil) REFERENCES Perfil(id_perfil) ON DELETE CASCADE
);

-- Carga inicial dos itens padrão disponíveis na loja do jogo
INSERT INTO Loja (nomeItem, tipoItem, valorEfeito, statusCura, valorItem, slugApi) VALUES
('Tô Confusa',          'hp',       20,   NULL,           100,  'potion'),
('Saudades bb',         'hp',       50,   NULL,           250,  'super-potion'),
('Recaída Forte',       'hp',       200,  NULL,           600,  'hyper-potion'),
('Volta Vida',          'hp',       NULL, NULL,           1200, 'max-potion'),
('Zerar o Passado',     'hp',       NULL, 'todos',        1800, 'full-restore'),
('Detox de Confusão',   'status',   NULL, 'veneno',       100,  'antidote'),
('Esfriar a Cabeça',    'status',   NULL, 'queimadura',   150,  'burn-heal'),
('Coração Derretido',   'status',   NULL, 'congelamento', 150,  'ice-heal'),
('Acorda pra Vida',     'status',   NULL, 'sono',         150,  'awakening'),
('Desbloqueado',        'status',   NULL, 'paralisia',    150,  'paralyze-heal'),
('Eu Já Superei',       'status',   NULL, 'todos',        400,  'full-heal'),
('Eu Vou mais eu Volto','reviver',  50,   NULL,           1000, 'revive'),
('Pedra Preciosa',      'evolucao', 20,   NULL,           1800, 'moon-stone');

-- View que lista os itens da loja ordenados por categoria (hp, status, reviver, evolucao) e menor preço
CREATE VIEW vw_loja_itens AS
SELECT id_item, nomeItem, tipoItem, valorEfeito, statusCura, valorItem, slugApi
FROM Loja
ORDER BY FIELD(tipoItem, 'hp', 'status', 'reviver', 'evolucao'), valorItem;

-- View que retorna apenas os itens com quantidade maior que zero na mochila de cada perfil, já com os detalhes da loja
CREATE VIEW vw_mochila_usuario AS
SELECT 
    m.id_perfil, 
    m.id_item, 
    l.nomeItem, 
    l.tipoItem, 
    l.valorEfeito, 
    l.statusCura, 
    l.valorItem, 
    l.slugApi, 
    m.quantidade
FROM Mochila m
JOIN Loja l ON m.id_item = l.id_item
WHERE m.quantidade > 0;