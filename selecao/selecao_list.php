<?php
require_once '../banco_de_dados/db.php';
require_once 'selecao.php';
require_once '../partida/partida.php';

$p = new Partida($pdo);
$p->recalcularPontos();
$selecao = new Selecao($pdo);
$selecoes = $selecao->listar();
$erro = null;

if (isset($_GET['del'])) {
    try {
        $selecao->excluir($_GET['del']);
        header("Location: selecao_list.php");
        exit;
    } catch (Exception $e) {
        $erro = $e->getMessage();
    }
}

?>

<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Copa do Mundo</title>
</head>

<body>
    <h1>Seleções</h1>
    <?php if ($erro): ?>
        <script>
            alert(<?= json_encode($erro) ?>);
        </script>
    <?php endif; ?>
    <a href="selecao_form.php">Nova Seleção</a>
    <table border="1" cellpadding="5">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Grupo</th>
            <th>Técnico</th>
            <th>Pontos</th>
        </tr>
        <?php foreach ($selecoes as $s): ?>
            <tr>
                <td><?= $s['id'] ?></td>
                <td><?= htmlspecialchars($s['nome']) ?></td>
                <td><?= htmlspecialchars($s['grupo']) ?></td>
                <td><?= htmlspecialchars($s['tecnico']) ?></td>
                <td><?= htmlspecialchars($s['pontos']) ?></td>
                <td>
                    <a href="selecao_form.php?id=<?= $s['id'] ?>">Editar</a>
                    <a href="selecao_list.php?del=<?= $s['id'] ?>" onclick="return confirm('Excluir seleção?')">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="/AD2/index.php">Voltar</a>
</body>

</html>