DROP DATABASE IF EXISTS meu_jogo;
CREATE DATABASE meu_jogo;
USE meu_jogo;

CREATE TABLE Usuario(
    id_usuario int AUTO_INCREMENT PRIMARY KEY,
    nome  varchar(100) NOT NULL,
    email varchar(150) UNIQUE NOT NULL,
    senha varchar(150) NOT NULL,
    moedas int NOT NULL DEFAULT 0
);

CREATE TABLE Pokemon(
    id_Pokemon int AUTO_INCREMENT PRIMARY KEY,
    nomePokemon varchar(30) NOT NULL,
    velocidade int NOT NULL,
    vida int NOT NULL DEFAULT 100,
    tipo varchar(20) NOT NULL,
    vidaMaxima int NOT NULL DEFAULT 100,
    nivel int NOT NULL DEFAULT 1,
    ataque int NOT NULL,
    defesa int NOT NULL,
    fraqueza varchar(20) NOT NULL,
    statusAtual varchar(20) NOT NULL DEFAULT 'Saudável'
);

CREATE TABLE Loja(
    id_item int AUTO_INCREMENT PRIMARY KEY,
    valorItem int NOT NULL,
    quantidadeItem int NOT NULL DEFAULT 0,
    tipoItem varchar(20) NOT NULL
);


USE meu_jogo;


ALTER TABLE Loja
  ADD COLUMN nomeItem   varchar(40) NOT NULL DEFAULT '' AFTER id_item,
  ADD COLUMN valorEfeito int NULL,          -- HP recuperado (NULL = 100%)
  ADD COLUMN statusCura  varchar(20) NULL,  -- veneno, queimadura, congelamento, sono, paralisia, todos
  ADD COLUMN slugApi     varchar(40) NULL;  

CREATE TABLE Mochila(
    id_usuario int NOT NULL,
    id_item    int NOT NULL,
    quantidade int NOT NULL DEFAULT 0,
    PRIMARY KEY (id_usuario, id_item),
    FOREIGN KEY (id_usuario) REFERENCES Usuario(id_usuario),
    FOREIGN KEY (id_item)    REFERENCES Loja(id_item)
);

CREATE TABLE LogCompra(
    id_log      int AUTO_INCREMENT PRIMARY KEY,
    id_usuario  int NOT NULL,
    id_item     int NOT NULL,
    quantidade  int NOT NULL,
    valorTotal  int NOT NULL,
    dataCompra  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES Usuario(id_usuario),
    FOREIGN KEY (id_item)    REFERENCES Loja(id_item)
);

-- ========== DML ==========
INSERT INTO Loja (nomeItem, tipoItem, valorEfeito, statusCura, valorItem, slugApi) VALUES
('Potion',          'hp',       20,   NULL,           200,  'potion'),
('Super Potion',    'hp',       50,   NULL,           700,  'super-potion'),
('Hyper Potion',    'hp',       200,  NULL,           1500, 'hyper-potion'),
('Max Potion',      'hp',       NULL, NULL,           2500, 'max-potion'),
('Full Restore',    'hp',       NULL, 'todos',        3000, 'full-restore'),
('Antidote',        'status',   NULL, 'veneno',       100,  'antidote'),
('Burn Heal',       'status',   NULL, 'queimadura',   250,  'burn-heal'),
('Ice Heal',        'status',   NULL, 'congelamento', 250,  'ice-heal'),
('Awakening',       'status',   NULL, 'sono',         250,  'awakening'),
('Paralyze Heal',   'status',   NULL, 'paralisia',    200,  'paralyze-heal'),
('Full Heal',       'status',   NULL, 'todos',        600,  'full-heal'),
('Revive',          'reviver',  NULL, NULL,           1500, 'revive'),
('Pedra Evolutiva', 'evolucao', NULL, NULL,           2000, 'moon-stone');



CREATE VIEW vw_loja_itens AS
  SELECT id_item, nomeItem, tipoItem, valorEfeito, statusCura, valorItem, slugApi
  FROM Loja
  ORDER BY FIELD(tipoItem,'hp','status','reviver','evolucao'), valorItem;

CREATE VIEW vw_mochila_usuario AS
  SELECT m.id_usuario, m.id_item, l.nomeItem, l.tipoItem, m.quantidade
  FROM Mochila m
  JOIN Loja l ON l.id_item = m.id_item
  WHERE m.quantidade > 0;

CREATE VIEW vw_historico_compras AS
  SELECT c.id_log, c.id_usuario, l.nomeItem, c.quantidade, c.valorTotal, c.dataCompra
  FROM LogCompra c
  JOIN Loja l ON l.id_item = c.id_item
  ORDER BY c.dataCompra DESC;


DELIMITER $$
CREATE TRIGGER trg_moedas_nao_negativa BEFORE UPDATE ON Usuario
FOR EACH ROW
BEGIN
  IF NEW.moedas < 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Saldo nao pode ficar negativo';
  END IF;
END$$
DELIMITER ;