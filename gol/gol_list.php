<?php
require_once '../banco_de_dados/db.php';
require_once 'gol.php';

$gol = new Gol($pdo);
$gols = $gol->listar();

$temPartidaBloqueada = false;
foreach ($gols as $g) {
    if ($g['status'] !== 'em andamento') {
        $temPartidaBloqueada = true;
        break;
    }
}
?>

<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Copa do Mundo</title>
</head>

<body>

    <h1>Gols</h1>

    <a href="gol_form.php">Adicionar gol</a>

    <table border="1" cellpadding="5">
        <tr>
            <th>ID</th>
            <th>Partida</th>
            <th>Jogador</th>
            <th>Minuto</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>

        <?php foreach ($gols as $g): ?>
            <tr>
                <td><?= $g['id'] ?></td>
                <td><?= htmlspecialchars($g['partida_id']) ?></td>
                <td><?= htmlspecialchars($g['jogador_nome']) ?></td>
                <td><?= htmlspecialchars($g['minuto']) ?>'</td>
                <td><?= htmlspecialchars($g['status']) ?></td>
                <td>
                    <a href="gol_form.php?id=<?= $g['id'] ?>">Editar</a>
                    <a href="gol_form.php?del=<?= $g['id'] ?>" onclick="return confirm('Excluir gol?')">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <br>
    <a href="/AD2/index.php">Voltar</a>

</body>

</html>