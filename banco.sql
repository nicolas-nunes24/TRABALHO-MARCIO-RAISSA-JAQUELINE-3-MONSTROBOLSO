create table Usuario(
    id_Usuario int AUTO_INCREMENT PRIMARY KEY
    nome  varchar(100) NOT NULL, 
    email varchar(30) UNIQUE NOT NULL,
    senha varchar(15) NOT NULL 
);

create table Pokemon(
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

