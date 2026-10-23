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
);

