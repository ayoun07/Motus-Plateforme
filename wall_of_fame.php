<?php
session_start();
include('db.php');

// Récupérer les 10 meilleurs scores et victoires
$stmt = $pdo->query('SELECT username, score, victories FROM scores JOIN users ON scores.user_id = users.id ORDER BY score DESC LIMIT 10');
$scores = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Motus - Wall of Fame</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <h1>Wall of Fame</h1>
        <table>
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Score</th>
                    <th>Victoires</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($scores as $score): ?>
                    <tr>
                        <td><?= htmlspecialchars($score['username']) ?></td>
                        <td><?= htmlspecialchars($score['score']) ?></td>
                        <td><?= htmlspecialchars($score['victories']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="game.php">Retour au jeu</a>
    </div>
</body>

</html>