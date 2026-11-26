USE meu_jogo;


CREATE TABLE IF NOT EXISTS ForcamonEvoluido (
    id_perfil INT NOT NULL,
    id_forcamon INT NOT NULL,
    evoluido_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_perfil, id_forcamon),
    FOREIGN KEY (id_perfil) REFERENCES Perfil(id_perfil) ON DELETE CASCADE
);


UPDATE Loja SET valorItem = 1800 WHERE slugApi = 'moon-stone';


ALTER TABLE Perfil MODIFY moedas INT NOT NULL DEFAULT 650;
ALTER TABLE Usuario MODIFY moedas INT NOT NULL DEFAULT 650;