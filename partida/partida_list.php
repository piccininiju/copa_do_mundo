<?php
require_once '../banco_de_dados/db.php';
require_once 'partida.php';

$partida = new Partida($pdo);
$erro = null;

if (isset($_GET['del'])) {
    try {
        $partida->excluir($_GET['del']);
        header("Location: partida_list.php");
        exit;
    } catch (Exception $e) {
        $erro = $e->getMessage();
    }
}

$partidas = $partida->listar();
?>

<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Copa do Mundo</title>
</head>

<body>
    <h1>Partidas</h1>
    <?php if ($erro): ?>
        <script>
            alert(<?= json_encode($erro) ?>);
        </script>
    <?php endif; ?>
    <a href="partida_form.php">Nova Partida</a>
    <table border="1" cellpadding="5">
        <tr>
            <th>ID</th>
            <th>Seleção casa</th>
            <th>Seleção visitante</th>
            <th>Data da partida</th>
            <th>Estádio</th>
            <th>Placar</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($partidas as $p): ?>
            <tr>
                <td><?= $p['id'] ?></td>
                <td><?= htmlspecialchars($p['casa_nome']) ?></td>
                <td><?= htmlspecialchars($p['visitante_nome']) ?></td>
                <td><?= htmlspecialchars($p['data_partida']) ?></td>
                <td><?= htmlspecialchars($p['estadio']) ?></td>
                <td><?= htmlspecialchars($p['gols_casa']) . "x" . htmlspecialchars($p['gols_visitante']) ?></td>
                <td><?= htmlspecialchars($p['status']) ?></td>
                <td>
                    <a href="partida_form.php?id=<?= $p['id'] ?>">Editar</a><br>
                    <a href="partida_view.php?id=<?= $p['id'] ?>">Ver</a><br>
                    <a href="partida_list.php?del=<?= $p['id'] ?>"
                        onclick="return confirm('Excluir partida?')">
                        Excluir
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <br>
    <a href="/AD2/index.php">Voltar</a>
</body>

</html>