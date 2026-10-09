create table usuario(
    id_usuario int AUTO_INCREMENT PRIMARY KEY
    nome  varchar(100) NOT NULL, 
    email varchar(30) UNIQUE NOT NULL,
    senha varchar(15) NOT NULL 
);