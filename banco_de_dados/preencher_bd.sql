USE copa_do_mundo;

-- SELEÇÕES
INSERT INTO selecoes (nome, grupo, tecnico) VALUES
('Brasil', 'A', 'Felipao'),
('Argentina', 'A', 'Scaloni'),
('Alemanha', 'A', 'Flick');

-- JOGADORES

-- Brasil (id = 1)
INSERT INTO jogadores (nome, numero_camisa, posicao, selecao_id) VALUES
('Ronaldo', 9, 'Atacante', 1),
('Ronaldinho', 11, 'Atacante', 1),
('Kaka', 23, 'Meio', 1);

-- Argentina (id = 2)
INSERT INTO jogadores (nome, numero_camisa, posicao, selecao_id) VALUES
('Messi', 10, 'Atacante', 2),
('Di Maria', 11, 'Atacante', 2),
('Enzo Fernandez', 24, 'Meio', 2);

-- Alemanha (id = 3)
INSERT INTO jogadores (nome, numero_camisa, posicao, selecao_id) VALUES
('Neuer', 1, 'Goleiro', 3),
('Muller', 13, 'Atacante', 3),
('Kroos', 8, 'Meio', 3);

-- PARTIDAS

-- Brasil x Argentina (Brasil ganha)
INSERT INTO partidas 
(selecao_casa_id, selecao_visitante_id, data_partida, estadio, gols_casa, gols_visitante, status)
VALUES 
(1, 2, '2022-11-20 16:00:00', 'Maracana', 2, 1, 'finalizada');

-- Brasil x Alemanha (Brasil ganha)
INSERT INTO partidas 
(selecao_casa_id, selecao_visitante_id, data_partida, estadio, gols_casa, gols_visitante, status)
VALUES 
(1, 3, '2022-11-25 16:00:00', 'Maracana', 3, 1, 'finalizada');

-- Alemanha x Argentina (empate)
INSERT INTO partidas 
(selecao_casa_id, selecao_visitante_id, data_partida, estadio, gols_casa, gols_visitante, status)
VALUES 
(3, 2, '2022-11-30 16:00:00', 'Allianz Arena', 1, 1, 'finalizada');

-- GOLS

-- Jogo 1: Brasil x Argentina (2x1)
INSERT INTO gols (partida_id, jogador_id, minuto) VALUES
(1, 1, 10), -- Ronaldo
(1, 2, 30), -- Ronaldinho
(1, 4, 50); -- Messi

-- Jogo 2: Brasil x Alemanha (3x1)
INSERT INTO gols (partida_id, jogador_id, minuto) VALUES
(2, 1, 5),
(2, 2, 20),
(2, 3, 60),
(2, 8, 70); -- Muller

-- Jogo 3: Alemanha x Argentina (1x1)
INSERT INTO gols (partida_id, jogador_id, minuto) VALUES
(3, 8, 40), -- Muller
(3, 4, 80); -- Messi