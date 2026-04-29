<?php
require_once '../banco_de_dados/db.php';
require_once 'gol.php';

$gol = new Gol($pdo);

$id = $_GET['id'] ?? null;
$partida_id = null;
$jogador_id = null;
$minuto = null;

if (isset($_GET['del'])) {
    $gol->excluir($_GET['del']);
    header("Location: gol_list.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $partida_id = $_POST['partida_id'] ?? null;
    $jogador_id = $_POST['jogador_id'] ?? null;
    $minuto = $_POST['minuto'] ?? null;

    if (!$partida_id || !$jogador_id || $minuto === null) {
        die("Todos os campos são obrigatórios");
    }

    if ($id) {
        $gol->atualizar($id, $partida_id, $jogador_id, $minuto);
    } else {
        $gol->inserir($partida_id, $jogador_id, $minuto);
    }

    header("Location: gol_list.php");
    exit;
}

if ($id) {
    $dados = $gol->buscar($id);
    if ($dados) {
        $partida_id = $dados['partida_id'];
        $jogador_id = $dados['jogador_id'];
        $minuto = $dados['minuto'];
    }
}

$partidas = $pdo->query("SELECT p.id, s1.nome AS casa, s2.nome AS visitante
    FROM partidas p
    JOIN selecoes s1 ON p.selecao_casa_id = s1.id
    JOIN selecoes s2 ON p.selecao_visitante_id = s2.id
    WHERE p.status = 'em andamento'
")->fetchAll(PDO::FETCH_ASSOC);
$jogadores = $pdo->query("SELECT id, nome FROM jogadores")->fetchAll(PDO::FETCH_ASSOC);
?>

<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Gol</title>
</head>

<body>

    <h1><?= $id ? "Editar gol" : "Novo gol" ?></h1>
    <?php if (empty($partidas)): ?>
        <p style="color:red;">
            Não há partidas em andamento. Não é possível registrar gols.
        </p>
    <?php endif; ?>
    <form method="post">
        <input type="hidden" name="id" value="<?= $id ?>">

        <label>Partida:</label><br>
        <select name="partida_id" required>
            <option value="">Selecione</option>
            <?php foreach ($partidas as $p): ?>
                <option value="<?= $p['id'] ?>" <?= ($p['id'] == $partida_id) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($p['casa']) ?> x <?= htmlspecialchars($p['visitante']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label>Jogador:</label><br>
        <select name="jogador_id" required>
            <option value="">Selecione</option>
            <?php foreach ($jogadores as $j): ?>
                <option value="<?= $j['id'] ?>" <?= ($j['id'] == $jogador_id) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($j['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label>Minuto:</label><br>
        <input type="number" name="minuto" value="<?= htmlspecialchars($minuto) ?>" required>
        <br><br>

        <?php if (!empty($partidas)): ?>
            <button type="submit">Salvar</button>
        <?php endif; ?> <a href="gol_list.php">Voltar</a>
    </form>

</body>

</html>