<?php

class Gol
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function listar()
    {
        $stmt = "SELECT g.id, g.partida_id, g.jogador_id, g.minuto, j.nome AS jogador_nome, p.status
                FROM gols g
                JOIN jogadores j ON g.jogador_id = j.id
                JOIN partidas p ON g.partida_id = p.id
                ORDER BY g.id DESC";

        return $this->pdo->query($stmt)->fetchAll(PDO::FETCH_ASSOC);
    }

    private function validarPartidaEmAndamento($partida_id)
    {
        $stmt = $this->pdo->prepare("SELECT status FROM partidas WHERE id = ?");
        $stmt->execute([$partida_id]);

        $status = $stmt->fetchColumn();

        if (!$status) {
            throw new Exception("Partida não encontrada.");
        }

        if ($status !== 'em andamento') {
            throw new Exception("Só é possível registrar gols em partidas em andamento.");
        }
    }

    private function validarJogadorNaPartida($partida_id, $jogador_id)
    {
        $sql = "SELECT COUNT(*) 
                FROM jogadores j
                JOIN partidas p ON p.id = ?
                WHERE j.id = ?
                AND (
                    j.selecao_id = p.selecao_casa_id
                    OR j.selecao_id = p.selecao_visitante_id
                )";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$partida_id, $jogador_id]);

        if ($stmt->fetchColumn() == 0) {
            throw new Exception("O jogador não pertence a nenhuma das seleções dessa partida.");
        }
    }

    public function inserir($partida_id, $jogador_id, $minuto)
    {
        //assumi que os gols só são inseridos em partidas em andamento e não finalizadas, como está no enunciado, 
        //pois assim quando finalizar a partida será possível atualizar os pontos
        $this->validarPartidaEmAndamento($partida_id);
        $this->validarJogadorNaPartida($partida_id, $jogador_id);

        $stmt = $this->pdo->prepare(" INSERT INTO gols (partida_id, jogador_id, minuto) VALUES (?, ?, ?)");

        return $stmt->execute([$partida_id, $jogador_id, $minuto]);
    }

    public function atualizar($id, $partida_id, $jogador_id, $minuto)
    {
        $this->validarPartidaEmAndamento($partida_id);
        $this->validarJogadorNaPartida($partida_id, $jogador_id);

        $stmt = $this->pdo->prepare("UPDATE gols 
            SET partida_id = ?, jogador_id = ?, minuto = ?
            WHERE id = ?
        ");

        return $stmt->execute([$partida_id, $jogador_id, $minuto, $id]);
    }

    public function excluir($id)
    {

        $stmt = $this->pdo->prepare("SELECT p.status
            FROM gols g
            JOIN partidas p ON g.partida_id = p.id
            WHERE g.id = ?
        ");
        $stmt->execute([$id]);

        $status = $stmt->fetchColumn();

        if ($status !== 'em andamento') {
            throw new Exception("Só é possível excluir gols de partidas em andamento.");
        }

        $stmt = $this->pdo->prepare("DELETE FROM gols WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function buscar($id)
    {
        $stmt = $this->pdo->prepare("SELECT g.*, j.nome AS jogador_nome
            FROM gols g
            JOIN jogadores j ON g.jogador_id = j.id
            WHERE g.id = ?
        ");

        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
