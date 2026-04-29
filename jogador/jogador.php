<?php

class Jogador
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function listar()
    {
        $stmt = $this->pdo->query("SELECT j.*, s.nome AS selecao_nome
            FROM jogadores j
            JOIN selecoes s ON j.selecao_id = s.id
            ORDER BY j.id
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function excluir($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM jogadores WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function inserir($nome, $numero_camisa, $posicao, $selecao_id)
    {
        $stmt = $this->pdo->prepare("INSERT INTO jogadores (nome, numero_camisa, posicao, selecao_id) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$nome, $numero_camisa, $posicao, $selecao_id]);
    }

    public function atualizar($id, $nome, $numero_camisa, $posicao, $selecao_id)
    {
        if (!$selecao_id) {
            throw new Exception("Seleção obrigatória");
        }
        $stmt = $this->pdo->prepare("UPDATE jogadores SET nome = ?, numero_camisa = ?, posicao = ?, selecao_id = ? WHERE id = ?");
        return $stmt->execute([$nome, $numero_camisa, $posicao, $selecao_id, $id]);
    }

    public function buscar($id)
    {
        $stmt = $this->pdo->prepare("SELECT j.*, s.nome AS selecao_nome
            FROM jogadores j
            JOIN selecoes s ON j.selecao_id = s.id
            WHERE j.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function nomeExiste($nome, $selecao_id, $id = null)
    {
        $sql = "SELECT COUNT(*) FROM jogadores 
            WHERE nome = ? AND selecao_id = ?";

        $params = [$nome, $selecao_id];

        if ($id) {
            $sql .= " AND id != ?";
            $params[] = $id;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchColumn() > 0;
    }
}
