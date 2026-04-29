<?php
require_once '../banco_de_dados/db.php';
require_once 'jogador.php';


$jogador = new Jogador($pdo);
$jogadores = $jogador->listar();
?>

<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Copa do Mundo</title>
</head>

<body>
    <h1>Jogadores</h1>
    <a href="jogador_form.php">Adicionar jogador</a>
    <table border="1" cellpadding="5">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Numero camisa</th>
            <th>Posição</th>
            <th>Seleção</th>
        </tr>
        <?php foreach ($jogadores as $j): ?>
            <tr>
                <td><?= $j['id'] ?></td>
                <td><?= htmlspecialchars($j['nome']) ?></td>
                <td><?= htmlspecialchars($j['numero_camisa']) ?></td>
                <td><?= htmlspecialchars($j['posicao']) ?></td>
                <td><?= htmlspecialchars($j['selecao_nome']) ?></td>
                <td>
                    <a href="jogador_form.php?id=<?= $j['id'] ?>">Editar</a>
                    <a href="jogador_form.php?del=<?= $j['id'] ?>" onclick="return confirm('Excluir jogador?')">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="/AD2/index.php">Voltar</a>
</body>

</html>