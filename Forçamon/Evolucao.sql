-- Seleciona o banco de dados do jogo para aplicar as alterações
USE meu_jogo;

-- Cria a tabela de controle de Forçamons evoluídos por perfil, caso ela ainda não exista
CREATE TABLE IF NOT EXISTS ForcamonEvoluido (
    id_perfil INT NOT NULL,
    id_forcamon INT NOT NULL,
    evoluido_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_perfil, id_forcamon),
    FOREIGN KEY (id_perfil) REFERENCES Perfil(id_perfil) ON DELETE CASCADE
);

-- Atualiza o preço da Pedra Preciosa (moon-stone) na loja para 1800 moedas
UPDATE Loja SET valorItem = 1800 WHERE slugApi = 'moon-stone';

-- Define o saldo inicial padrão de moedas como 650 para novos registros em Perfil e Usuario
ALTER TABLE Perfil MODIFY moedas INT NOT NULL DEFAULT 650;
ALTER TABLE Usuario MODIFY moedas INT NOT NULL DEFAULT 650;