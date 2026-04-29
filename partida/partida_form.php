<?php
require_once '../banco_de_dados/db.php';
require_once 'partida.php';

$partida = new Partida($pdo);

$selecao_casa_id = $selecao_visitante_id = $data_partida = null;
$estadio = $status = '';
$id = $_GET['id'] ?? null;
$erro = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $selecao_casa_id = $_POST['selecao_casa_id'] ?? null;
    $selecao_visitante_id = $_POST['selecao_visitante_id'] ?? null;
    $data_partida = $_POST['data_partida'] ?? null;
    $estadio = $_POST['estadio'] ?? '';
    $status = $_POST['status'] ?? '';

    if ($selecao_casa_id == $selecao_visitante_id) {
        $erro = "Seleção casa e visitante não podem ser iguais";
    } else {
        if ($id) {
            $partida->atualizar($id, $selecao_casa_id, $selecao_visitante_id, $data_partida, $estadio, $status);
        } else {
            $partida->inserir($selecao_casa_id, $selecao_visitante_id, $data_partida, $estadio, $status);
        }

        header("Location: partida_list.php");
        exit;
    }
}

if ($id) {
    $dados = $partida->buscar($id);

    $selecao_casa_id = $dados['selecao_casa_id'] ?? null;
    $selecao_visitante_id = $dados['selecao_visitante_id'] ?? null;
    $data_partida = $dados['data_partida'] ?? null;
    $estadio = $dados['estadio'] ?? '';
    $status = $dados['status'] ?? '';
}

$selecoes = $pdo->query("SELECT id, nome FROM selecoes")->fetchAll(PDO::FETCH_ASSOC);
?>

<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Copa do Mundo</title>
</head>

<body>

    <h1><?= $id ? "Editar partida" : "Nova partida" ?></h1>

    <?php if ($erro): ?>
        <p style="color:red"><?= $erro ?></p>
    <?php endif; ?>

    <form method="post">
        <input type="hidden" name="id" value="<?= $id ?>">

        <label>Seleção casa:</label><br>
        <select name="selecao_casa_id" required>
            <option value="">Selecione</option>
            <?php foreach ($selecoes as $s): ?>
                <option value="<?= $s['id'] ?>" <?= $s['id'] == $selecao_casa_id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($s['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Seleção visitante:</label><br>
        <select name="selecao_visitante_id" required>
            <option value="">Selecione</option>
            <?php foreach ($selecoes as $s): ?>
                <option value="<?= $s['id'] ?>" <?= $s['id'] == $selecao_visitante_id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($s['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Data e hora:</label><br>
        <input type="datetime-local"
            name="data_partida"
            value="<?= $data_partida ? date('Y-m-d\TH:i', strtotime($data_partida)) : '' ?>"
            required><br><br>

        <label>Estádio:</label><br>
        <input type="text" name="estadio" value="<?= htmlspecialchars($estadio) ?>" required><br><br>

        <label>Status:</label><br>
        <select name="status" required>
            <option value="agendada" <?= $status == 'agendada' ? 'selected' : '' ?>>Agendada</option>
            <option value="em andamento" <?= $status == 'em andamento' ? 'selected' : '' ?>>Em andamento</option>
            <option value="finalizada" <?= $status == 'finalizada' ? 'selected' : '' ?>>Finalizada</option>
        </select><br><br>

        <button type="submit">Salvar</button>
        <a href="partida_list.php">Voltar</a>
    </form>

</body>

</html>