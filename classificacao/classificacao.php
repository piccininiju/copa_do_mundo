<?php
require_once '../banco_de_dados/db.php';

$stmt = "SELECT 
            s.id,
            s.nome,
            s.grupo,
            s.pontos,

            COALESCE(SUM(t.gols_pro), 0) AS gols_pro,
            COALESCE(SUM(t.gols_contra), 0) AS gols_contra,
            COALESCE(SUM(t.gols_pro - t.gols_contra), 0) AS saldo
        FROM selecoes s
        LEFT JOIN (
            SELECT 
                p.selecao_casa_id AS selecao_id,

                CASE 
                    WHEN p.gols_casa > p.gols_visitante THEN 3
                    WHEN p.gols_casa = p.gols_visitante THEN 1
                    ELSE 0
                END AS pontos,

                p.gols_casa AS gols_pro,
                p.gols_visitante AS gols_contra

            FROM partidas p
            WHERE p.status = 'finalizada'

            UNION ALL

            SELECT 
                p.selecao_visitante_id AS selecao_id,

                CASE 
                    WHEN p.gols_visitante > p.gols_casa THEN 3
                    WHEN p.gols_visitante = p.gols_casa THEN 1
                    ELSE 0
                END AS pontos,

                p.gols_visitante AS gols_pro,
                p.gols_casa AS gols_contra

            FROM partidas p
            WHERE p.status = 'finalizada'

        ) t ON s.id = t.selecao_id

        GROUP BY s.id, s.nome, s.grupo

        ORDER BY 
            pontos DESC,
            saldo DESC,
            gols_pro DESC
";

$classificacao = $pdo->query($stmt)->fetchAll(PDO::FETCH_ASSOC);
?>

<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Classificação</title>
</head>

<body>

    <h1>Classificação</h1>

    <table border="1" cellpadding="5">
        <tr>
            <th>Seleção</th>
            <th>Grupo</th>
            <th>Pontos</th>
            <th>Gols Pró</th>
            <th>Gols Contra</th>
            <th>Saldo</th>
        </tr>

        <?php foreach ($classificacao as $c): ?>
            <tr>
                <td><?= htmlspecialchars($c['nome']) ?></td>
                <td><?= htmlspecialchars($c['grupo']) ?></td>
                <td><?= $c['pontos'] ?></td>
                <td><?= $c['gols_pro'] ?></td>
                <td><?= $c['gols_contra'] ?></td>
                <td><?= $c['saldo'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <br>
    <a href="/AD2/index.php">Voltar</a>

</body>

</html>