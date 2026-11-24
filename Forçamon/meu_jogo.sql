DROP DATABASE IF EXISTS meu_jogo;
CREATE DATABASE meu_jogo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE meu_jogo;

CREATE TABLE Usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    moedas INT NOT NULL DEFAULT 500
);

CREATE TABLE Perfil (
    id_perfil INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    nome_treinador VARCHAR(60) NOT NULL,
    moedas INT NOT NULL DEFAULT 500,
    vitorias INT NOT NULL DEFAULT 0,
    derrotas INT NOT NULL DEFAULT 0,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES Usuario(id_usuario) ON DELETE CASCADE
);

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

CREATE TABLE Loja (
    id_item INT AUTO_INCREMENT PRIMARY KEY,
    nomeItem VARCHAR(40) NOT NULL,
    tipoItem ENUM('hp', 'status', 'reviver', 'evolucao') NOT NULL,
    valorEfeito INT NULL,
    statusCura VARCHAR(20) NULL,
    valorItem INT NOT NULL,
    slugApi VARCHAR(40) NULL
);

CREATE TABLE Mochila (
    id_perfil INT NOT NULL,
    id_item INT NOT NULL,
    quantidade INT NOT NULL DEFAULT 0,
    PRIMARY KEY (id_perfil, id_item),
    FOREIGN KEY (id_perfil) REFERENCES Perfil(id_perfil) ON DELETE CASCADE,
    FOREIGN KEY (id_item) REFERENCES Loja(id_item)
);

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

INSERT INTO Loja (nomeItem, tipoItem, valorEfeito, statusCura, valorItem, slugApi) VALUES
('Tô Confusa',        'hp',       20,   NULL,           100,  'potion'),
('Saudades bb',     'hp',       50,   NULL,           250,  'super-potion'),
('Recaída Forte',     'hp',       200,  NULL,           600,  'hyper-potion'),
('Volta Vida',        'hp',       NULL, NULL,           1200, 'max-potion'),
('Zerar o Passado',   'hp',       NULL, 'todos',        1800, 'full-restore'),
('Detox de Confusão',       'status',   NULL, 'veneno',       100,  'antidote'),
('Esfriar a Cabeça',  'status',   NULL, 'queimadura',   150,  'burn-heal'),
('Coração Derretido', 'status',   NULL, 'congelamento', 150,  'ice-heal'),
('Acorda pra Vida',   'status',   NULL, 'sono',         150,  'awakening'),
('Desbloqueado',      'status',   NULL, 'paralisia',    150,  'paralyze-heal'),
('Eu Já Superei',     'status',   NULL, 'todos',        400,  'full-heal'),
('Eu Vou mais eu Volto',  'reviver',  50,   NULL,           1000, 'revive'),
('Pedra Preciosa',    'evolucao', 20,   NULL,           2000, 'moon-stone');

CREATE VIEW vw_loja_itens AS
SELECT id_item, nomeItem, tipoItem, valorEfeito, statusCura, valorItem, slugApi
FROM Loja
ORDER BY FIELD(tipoItem, 'hp', 'status', 'reviver', 'evolucao'), valorItem;

CREATE VIEW vw_mochila_usuario AS
SELECT m.id_perfil, m.id_item, l.nomeItem, l.tipoItem, l.valorEfeito, l.statusCura, m.quantidade
FROM Mochila m
JOIN Loja l ON l.id_item = m.id_item
WHERE m.quantidade > 0;

CREATE VIEW vw_historico_batalhas AS
SELECT b.id_batalha, p.nome_treinador, b.time_jogador, b.time_oponente, b.resultado, b.turnos, b.moedas_ganhas, b.data_batalha
FROM LogBatalha b
JOIN Perfil p ON p.id_perfil = b.id_perfil
ORDER BY b.data_batalha DESC;

DELIMITER $$
CREATE TRIGGER trg_moedas_nao_negativa BEFORE UPDATE ON Perfil
FOR EACH ROW
BEGIN
    IF NEW.moedas < 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Saldo nao pode ficar negativo';
    END IF;
END$$
DELIMITER ;