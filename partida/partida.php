<?php

class Partida
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function listar()
    {
        $sql = "SELECT 
                    p.*,
                    s1.nome AS casa_nome,
                    s2.nome AS visitante_nome,

                    (
                        SELECT COUNT(*)
                        FROM gols g
                        JOIN jogadores j ON g.jogador_id = j.id
                        WHERE g.partida_id = p.id
                        AND j.selecao_id = p.selecao_casa_id
                    ) AS gols_casa,

                    (
                        SELECT COUNT(*)
                        FROM gols g
                        JOIN jogadores j ON g.jogador_id = j.id
                        WHERE g.partida_id = p.id
                        AND j.selecao_id = p.selecao_visitante_id
                    ) AS gols_visitante

                FROM partidas p
                JOIN selecoes s1 ON p.selecao_casa_id = s1.id
                JOIN selecoes s2 ON p.selecao_visitante_id = s2.id
                ORDER BY p.id";

        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function excluir($id)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM gols WHERE partida_id = ?");
        $stmt->execute([$id]);

        if ($stmt->fetchColumn() > 0) {
            throw new Exception("Não é possível excluir: existem gols vinculados a essa partida.");
        }

        $stmt = $this->pdo->prepare("DELETE FROM partidas WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function inserir($selecao_casa_id, $selecao_visitante_id, $data_partida, $estadio, $status)
    {
        if ($selecao_casa_id == $selecao_visitante_id) {
            throw new Exception("Seleções não podem ser iguais.");
        }

        $stmt = $this->pdo->prepare("
            INSERT INTO partidas 
            (selecao_casa_id, selecao_visitante_id, data_partida, estadio, status) 
            VALUES (?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $selecao_casa_id,
            $selecao_visitante_id,
            $data_partida,
            $estadio,
            $status
        ]);
    }

    private function atualizarPlacar($partida_id)
    {
        $sqlCasa = "SELECT COUNT(*) 
        FROM gols g
        JOIN jogadores j ON g.jogador_id = j.id
        JOIN partidas p ON g.partida_id = p.id
        WHERE g.partida_id = ?
        AND j.selecao_id = p.selecao_casa_id
    ";

        $stmt = $this->pdo->prepare($sqlCasa);
        $stmt->execute([$partida_id]);
        $gols_casa = $stmt->fetchColumn();

        $sqlVisitante = "SELECT COUNT(*) 
        FROM gols g
        JOIN jogadores j ON g.jogador_id = j.id
        JOIN partidas p ON g.partida_id = p.id
        WHERE g.partida_id = ?
        AND j.selecao_id = p.selecao_visitante_id
    ";

        $stmt = $this->pdo->prepare($sqlVisitante);
        $stmt->execute([$partida_id]);
        $gols_visitante = $stmt->fetchColumn();

        $update = $this->pdo->prepare("
        UPDATE partidas 
        SET gols_casa = ?, gols_visitante = ?
        WHERE id = ?
    ");

        $update->execute([$gols_casa, $gols_visitante, $partida_id]);
    }

    public function atualizar($id, $selecao_casa_id, $selecao_visitante_id, $data_partida, $estadio, $status)
    {
        if ($selecao_casa_id == $selecao_visitante_id) {
            throw new Exception("Seleções não podem ser iguais.");
        }

        $stmt = $this->pdo->prepare("UPDATE partidas 
        SET 
            selecao_casa_id = ?, 
            selecao_visitante_id = ?, 
            data_partida = ?, 
            estadio = ?, 
            status = ?
        WHERE id = ?
    ");

        $stmt->execute([
            $selecao_casa_id,
            $selecao_visitante_id,
            $data_partida,
            $estadio,
            $status,
            $id
        ]);

        if ($status === 'finalizada') {
            $this->atualizarPlacar($id);
            $this->recalcularPontos();
        }

        return true;
    }

    public function buscar($id)
    {
        $stmt = $this->pdo->prepare("SELECT 
                p.*,
                s1.nome AS casa_nome,
                s2.nome AS visitante_nome,

                (
                    SELECT COUNT(*)
                    FROM gols g
                    JOIN jogadores j ON g.jogador_id = j.id
                    WHERE g.partida_id = p.id
                    AND j.selecao_id = p.selecao_casa_id
                ) AS gols_casa,

                (
                    SELECT COUNT(*)
                    FROM gols g
                    JOIN jogadores j ON g.jogador_id = j.id
                    WHERE g.partida_id = p.id
                    AND j.selecao_id = p.selecao_visitante_id
                ) AS gols_visitante

            FROM partidas p
            JOIN selecoes s1 ON p.selecao_casa_id = s1.id
            JOIN selecoes s2 ON p.selecao_visitante_id = s2.id
            WHERE p.id = ?
        ");

        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function recalcularPontos()
    {
        $this->pdo->exec("UPDATE selecoes SET pontos = 0");

        $stmt = $this->pdo->query("
        SELECT selecao_casa_id, selecao_visitante_id, gols_casa, gols_visitante
        FROM partidas
        WHERE status = 'finalizada'
    ");

        $partidas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($partidas as $p) {

            $casa = $p['selecao_casa_id'];
            $visitante = $p['selecao_visitante_id'];

            if ($p['gols_casa'] > $p['gols_visitante']) {
                $this->pdo->prepare("UPDATE selecoes SET pontos = pontos + 3 WHERE id = ?")
                    ->execute([$casa]);
            } elseif ($p['gols_visitante'] > $p['gols_casa']) {
                $this->pdo->prepare("UPDATE selecoes SET pontos = pontos + 3 WHERE id = ?")
                    ->execute([$visitante]);
            } else {
                $this->pdo->prepare("UPDATE selecoes SET pontos = pontos + 1 WHERE id = ?")
                    ->execute([$casa]);

                $this->pdo->prepare("UPDATE selecoes SET pontos = pontos + 1 WHERE id = ?")
                    ->execute([$visitante]);
            }
        }
    }
}
