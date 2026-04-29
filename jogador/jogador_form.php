<?php
require_once '../banco_de_dados/db.php';
require_once 'jogador.php';

$jogador = new Jogador($pdo);
$nome = $posicao = '';
$numero_camisa = $selecao_id = null;
$id = $_GET['id'] ?? null;

if (isset($_GET['del'])) {
    $jogador->excluir($_GET['del']);
    header("Location: jogador_list.php");
    exit;
}

$erro = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $nome = $_POST['nome'] ?? '';
    $numero_camisa = $_POST['numero_camisa'] ?? '';
    $posicao = $_POST['posicao'] ?? '';
    $selecao_id = $_POST['selecao_id'] ?? null;

    if (!$selecao_id) {
        $erro = "Seleção obrigatória";
    } elseif ($jogador->nomeExiste($nome, $selecao_id, $id)) {
        $erro = "Já existe um jogador com esse nome nessa seleção.";
    } else {
        if ($id) {
            $jogador->atualizar($id, $nome, $numero_camisa, $posicao, $selecao_id);
        } else {
            $jogador->inserir($nome, $numero_camisa, $posicao, $selecao_id);
        }

        header("Location: jogador_list.php");
        exit;
    }
}

if ($id) {
    $dados = $jogador->buscar($id);
    $nome = $dados['nome'] ?? '';
    $numero_camisa = $dados['numero_camisa'] ?? '';
    $posicao = $dados['posicao'] ?? '';
    $selecao_id = $dados['selecao_id'] ?? null;
}

$selecoes = $pdo->query("SELECT id, nome FROM selecoes")->fetchAll(PDO::FETCH_ASSOC);
?>

<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Copa do Mundo</title>
</head>

<body>
    <h1><?= $id ? "Editar jogadores" : "Novo jogador" ?></h1>
    <?php if ($erro): ?>
        <script>
            alert('<?= $erro ?>');
        </script>
    <?php endif; ?>
    <form method="post">
        <input type="hidden" name="id" value="<?= $id ?>">
        <label>Nome: </label><br>
        <input type="text" name="nome" value="<?= htmlspecialchars($nome) ?>" required><br>
        <label>Número camisa: </label><br>
        <input type="number" name="numero_camisa" value="<?= htmlspecialchars($numero_camisa) ?>" required><br>
        <label>Posição: </label><br>
        <input type="text" name="posicao" value="<?= htmlspecialchars($posicao) ?>" required><br>
        <label>Seleção: </label><br>
        <select name="selecao_id" required>
            <option value="">Selecione uma seleção</option>

            <?php foreach ($selecoes as $s): ?>
                <option value="<?= $s['id'] ?>"
                    <?= $s['id'] == $selecao_id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($s['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Salvar</button>
        <a href="jogador_list.php">Voltar</a>
    </form>

</html>