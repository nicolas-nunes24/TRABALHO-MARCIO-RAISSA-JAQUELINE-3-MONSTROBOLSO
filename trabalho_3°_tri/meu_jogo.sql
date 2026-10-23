<<<<<<< HEAD:trabalho_3°_tri/meu_jogo.sql
DROP DATABASE meu_jogo;
CREATE DATABASE if NOT EXISTS meu_jogo;
USE meu_jogo;

CREATE TABLE if NOT EXISTS Usuario(
    id_Usuario int AUTO_INCREMENT PRIMARY KEY,
    nome  varchar(100) NOT NULL, 
    email varchar(150) UNIQUE NOT NULL,
    senha varchar(150) NOT NULL ,
    moedas int NOT NULL DEFAULT 0
);

CREATE TABLE if NOT EXISTS Pokemon(
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

CREATE TABLE if NOT EXISTS Loja(
    id_item int AUTO_INCREMENT PRIMARY KEY,
    valorItem int NOT NULL,
    quantidadeItem int NOT NULL DEFAULT 0,
    tipoItem varchar(20) NOT NULL
=======
CREATE DATABASE IF NOT EXISTS meu_jogo DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE meu_jogo;

CREATE TABLE IF NOT EXISTS Usuario(
    id_Usuario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) UNIQUE NOT NULL, 
    email VARCHAR(150) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    moedas INT DEFAULT 0
);

CREATE TABLE IF NOT EXISTS Pokemon(
    id_Pokemon INT AUTO_INCREMENT PRIMARY KEY,
    nomePokemon VARCHAR(30) NOT NULL,
    velocidade INT NOT NULL,
    vida INT NOT NULL DEFAULT 100,
    tipo VARCHAR(20) NOT NULL,
    vidaMaxima INT NOT NULL DEFAULT 100,
    nivel INT NOT NULL DEFAULT 1,
    ataque INT NOT NULL,
    defesa INT NOT NULL,
    fraqueza VARCHAR(20) NOT NULL,
    statusAtual VARCHAR(20) NOT NULL DEFAULT 'Saudável'
>>>>>>> 79f7883b41261b29acac70dd18f86864678bb739:trabalho_3°_tri/banco.sql
);

CREATE TABLE IF NOT EXISTS Loja(
    id_item INT AUTO_INCREMENT PRIMARY KEY,
    valorItem INT NOT NULL,
    quantidadeItem INT NOT NULL DEFAULT 0,
    tipoItem VARCHAR(20) NOT NULL
);