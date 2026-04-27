<?php

class Selecao
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function listar()
    {
        $stmt = $this->pdo->query("SELECT * FROM selecoes ORDER BY nome");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function excluir($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM selecoes WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function inserir($nome, $grupo, $tecnico, $pontos)
    {
        $stmt = $this->pdo->prepare("INSERT INTO selecoes (nome, grupo, tecnico, pontos) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$nome, $grupo, $tecnico, $pontos]);
    }

    public function atualizar($id, $nome, $grupo, $tecnico, $pontos)
    {
        $stmt = $this->pdo->prepare("UPDATE selecoes SET nome = ?, grupo = ?, tecnico = ?, pontos = ? WHERE id = ?");
        return $stmt->execute([$nome, $grupo, $tecnico, $pontos, $id]);
    }

    public function buscar($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM selecoes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
