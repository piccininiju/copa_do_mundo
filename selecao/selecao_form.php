<?php
require_once '../banco_de_dados/db.php';
require_once 'selecao.php';

$selecao = new Selecao($pdo);
$nome = $grupo = $tecnico = '';
$id = $_GET['id'] ?? null;
$erro = null;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $nome = $_POST['nome'] ?? '';
    $grupo = $_POST['grupo'] ?? '';
    $tecnico = $_POST['tecnico'] ?? '';

    if ($id) {
        $selecao->atualizar($id, $nome, $grupo, $tecnico);
    } else {
        $selecao->inserir($nome, $grupo, $tecnico);
    }
    header("Location: selecao_list.php");
    exit;
}

if ($id) {
    $dados = $selecao->buscar($id);
    $nome = $dados['nome'] ?? '';
    $grupo = $dados['grupo'] ?? '';
    $tecnico = $dados['tecnico'] ?? '';
}


?>

<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Copa do Mundo</title>
</head>

<body>
    <h1><?= $id ? "Editar seleção" : "Nova seleção" ?></h1>
    <form method="post">
        <input type="hidden" name="id" value="<?= $id ?>">
        <label>Nome: </label><br>
        <input type="text" name="nome" value="<?= htmlspecialchars($nome) ?>" required><br>
        <label>Grupo: </label><br>
        <input type="text" name="grupo" value="<?= htmlspecialchars($grupo) ?>" required><br>
        <label>Técnico: </label><br>
        <input type="text" name="tecnico" value="<?= htmlspecialchars($tecnico) ?>" required><br>
        <button type="submit">Salvar</button>
        <a href="selecao_list.php">Voltar</a>
    </form>
</body>

</html>