<?php
require_once '../banco_de_dados/db.php';
require_once 'partida.php';

$partida = new Partida($pdo);
$id = $_GET['id'] ?? null;

if (!$id) {
    echo "Partida não especificada.";
    exit;
}

$item = $partida->buscar($id);
?>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Copa do Mundo</title>
</head>

<body>
    <h1>Detalhes da partida #<?= $id ?></h1>
    <table border="1" cellpadding="5">
        <tr>
            <th>Seleção casa</th>
            <th>Seleção visitante</th>
            <th>Data da partida</th>
            <th>Estádio</th>
            <th>Gols da casa</th>
            <th>Gols do visitante</th>
            <th>Status</th>
        </tr>
        <tr>
            <td><?= $item['casa_nome'] ?></td>
            <td><?= $item['visitante_nome'] ?></td>
            <td><?= $item['data_partida'] ?></td>
            <td><?= $item['estadio'] ?></td>
            <td><?= $item['gols_casa'] ?></td>
            <td><?= $item['gols_visitante'] ?></td>
            <td><?= $item['status'] ?></td>
        </tr>
    </table>
    <br>
    <a href="/AD2/partida/partida_list.php">Voltar</a>
</body>

</html>