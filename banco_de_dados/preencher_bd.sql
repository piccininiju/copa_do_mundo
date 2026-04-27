USE copa_do_mundo;

INSERT INTO selecoes (nome, grupo, tecnico) VALUES
('Brasil', 'A', 'Felipao'),
('Alemanha', 'B', 'Rudi Voller');

-- Inserindo jogadores na selecao do brasil e da alemanha
INSERT INTO jogadores (nome, numero_camisa, posicao, selecao_id)
VALUES ('Ronaldo', 9, 'Atacante', 1),
('Ronaldinho Gaúcho', 11, 'Atacante', 1),
('Kaká', 23, 'Meio', 1),
('Oliver Kahn', 1, 'Goleiro', 2),
('Carsten Jancker', 9, 'Atacante', 2),
('Torsten Frings', 22, 'Meio', 2);

INSERT INTO partidas 
(selecao_casa_id, selecao_visitante_id, data_partida, estadio, gols_casa, gols_visitante, status)
VALUES 
(2, 1, '2002-06-30 20:00:00', 'Estádio Olímpico de Berlim', 0, 2, 'finalizada');

INSERT INTO gols (partida_id, jogador_id, minuto) 
VALUES (1, 1, 22),
(1, 1, 34);