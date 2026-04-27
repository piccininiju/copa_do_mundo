CREATE DATABASE IF NOT EXISTS copa_do_mundo;
USE copa_do_mundo;

CREATE TABLE selecoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    grupo CHAR(1) NOT NULL,
    tecnico VARCHAR(100) NOT NULL,
    pontos INT DEFAULT 0
);

CREATE TABLE jogadores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    numero_camisa INT NOT NULL,
    posicao VARCHAR(50) NOT NULL,
    selecao_id INT NOT NULL,
    FOREIGN KEY (selecao_id) REFERENCES selecoes(id) ON DELETE CASCADE,
    UNIQUE (numero_camisa, selecao_id)
);

CREATE TABLE partidas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    selecao_casa_id INT NOT NULL,
    selecao_visitante_id INT NOT NULL,
    FOREIGN KEY (selecao_casa_id) REFERENCES selecoes(id) ON DELETE CASCADE,
    FOREIGN KEY (selecao_visitante_id) REFERENCES selecoes(id) ON DELETE CASCADE,
    data_partida DATETIME DEFAULT CURRENT_TIMESTAMP,
    estadio VARCHAR(100) NOT NULL,
    gols_casa INT NOT NULL DEFAULT 0,
    gols_visitante INT NOT NULL DEFAULT 0,
    status ENUM('agendada', 'finalizada', 'em andamento') DEFAULT 'agendada'
);

CREATE TABLE gols (
    id INT AUTO_INCREMENT PRIMARY KEY,
    partida_id INT NOT NULL,
    jogador_id INT NOT NULL,
    FOREIGN KEY (partida_id) REFERENCES partidas(id),
    FOREIGN KEY (jogador_id) REFERENCES jogadores(id),
    minuto INT NOT NULL
);