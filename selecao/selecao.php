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
        $stmt = $this->pdo->query("SELECT * FROM selecoes ORDER BY id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function excluir($id)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM jogadores WHERE selecao_id = ?");
        $stmt->execute([$id]);
        $total = $stmt->fetchColumn();

        if ($total > 0) {
            throw new Exception("Não é possível excluir: existem jogadores nessa seleção.");
        }

        $stmt = $this->pdo->prepare("DELETE FROM selecoes WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function inserir($nome, $grupo, $tecnico)
    {
        $stmt = $this->pdo->prepare("INSERT INTO selecoes (nome, grupo, tecnico) VALUES ( ?, ?, ?)");
        return $stmt->execute([$nome, $grupo, $tecnico]);
    }

    public function atualizar($id, $nome, $grupo, $tecnico)
    {
        $stmt = $this->pdo->prepare("UPDATE selecoes SET nome = ?, grupo = ?, tecnico = ? WHERE id = ?");
        return $stmt->execute([$nome, $grupo, $tecnico, $id]);
    }

    public function buscar($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM selecoes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
