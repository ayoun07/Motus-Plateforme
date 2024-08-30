<?php
session_start();
include('db.php'); // Assurez-vous que ce fichier configure correctement la connexion à la base de données.

// Sélectionner un mot aléatoire depuis la base de données
$query = $pdo->query("SELECT word FROM words ORDER BY RAND() LIMIT 1");

// Vérification de la requête
if ($query === false) {
    die('Erreur lors de la récupération du mot depuis la base de données.');
}

$result = $query->fetch(PDO::FETCH_ASSOC);

if ($result === false) {
    die('Aucun mot trouvé dans la base de données.');
}

$word = strtoupper($result['word']);
$firstLetter = $word[0];
$wordLength = strlen($word);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Motus</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .game-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 50px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(<?= $wordLength ?>, 50px);
            gap: 10px;
            margin-bottom: 20px;
            background-color: #1e90ff;
            /* Fond bleu pour la grille */
            padding: 10px;
            border-radius: 10px;
        }

        .cell {
            width: 50px;
            height: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 24px;
            border: 2px solid #ddd;
            text-transform: uppercase;
            background-color: blue;
            /* Fond bleu par défaut pour les lettres */
            color: white;
            position: relative;
        }

        .correct {
            background-color: red;
            /* Fond rouge rempli pour les lettres bien placées */
            color: white;
            border-radius: 0;
            /* Assurez-vous que c'est un carré */
        }

        .present {
            background-color: yellow;
            /* Fond jaune rempli pour les lettres mal placées mais présentes */
            color: black;
            border-radius: 50%;
            /* Cercle pour les lettres mal placées mais présentes */
        }

        .absent {
            background-color: blue;
            /* Fond bleu pour les lettres absentes */
            color: white;
        }
    </style>

</head>

<body>
    <div class="game-container">
        <h1>Motus</h1>
        <div class="grid" id="grid">
            <?php for ($i = 0; $i < 6; $i++): ?>
                <?php for ($j = 0; $j < $wordLength; $j++): ?>
                    <div class="cell" id="cell-<?= $i ?>-<?= $j ?>" data-row="<?= $i ?>" data-col="<?= $j ?>" contenteditable="<?= $i === 0 ? 'true' : 'false' ?>"></div>
                <?php endfor; ?>
            <?php endfor; ?>
        </div>
        <button id="submitGuess">Soumettre</button>
        <p id="message"></p>
    </div>
    <script>
        const word = '<?= $word ?>';
        let currentRow = 0;
        let currentCol = 0;
        const wordLength = <?= $wordLength ?>;

        // Initialiser la première lettre du mot
        document.getElementById('cell-0-0').textContent = word[0];
        document.getElementById('cell-0-0').setAttribute('contenteditable', 'false');

        document.addEventListener('keydown', function(event) {
            const key = event.key.toUpperCase();
            if (/[A-Z]/.test(key) && key.length === 1) {
                if (currentCol < wordLength) {
                    const cell = document.getElementById(`cell-${currentRow}-${currentCol}`);
                    cell.textContent = key;
                    currentCol++;
                }
            } else if (event.key === 'Backspace') {
                if (currentCol > 0) {
                    currentCol--;
                    const cell = document.getElementById(`cell-${currentRow}-${currentCol}`);
                    cell.textContent = '';
                }
            } else if (event.key === 'Enter') {
                submitGuess();
            }
        });

        document.getElementById('submitGuess').addEventListener('click', submitGuess);

        function submitGuess() {
            if (currentCol !== wordLength) {
                document.getElementById('message').textContent = 'Veuillez compléter toutes les lettres de la ligne.';
                return;
            }

            const guess = Array.from({
                length: wordLength
            }, (_, i) => document.getElementById(`cell-${currentRow}-${i}`).textContent).join('');
            if (guess.length !== wordLength) {
                document.getElementById('message').textContent = 'Le mot doit contenir ' + wordLength + ' lettres.';
                return;
            }

            fetch('check_guess.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'guess=' + guess + '&word=' + word
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        for (let i = 0; i < wordLength; i++) {
                            const cell = document.getElementById(`cell-${currentRow}-${i}`);
                            if (data.result[i] === 'correct') {
                                cell.classList.add('correct');
                            } else if (data.result[i] === 'present') {
                                cell.classList.add('present');
                            } else {
                                cell.classList.add('absent');
                            }
                            cell.setAttribute('contenteditable', 'false');
                        }

                        if (data.victory) {
                            document.getElementById('message').textContent = 'Félicitations, vous avez trouvé le mot !';
                            document.getElementById('submitGuess').disabled = true;
                        } else {
                            currentRow++;
                            currentCol = 0;
                            if (currentRow < 6) {
                                document.getElementById(`cell-${currentRow}-0`).textContent = word[0];
                                document.getElementById(`cell-${currentRow}-0`).setAttribute('contenteditable', 'false');
                            } else {
                                document.getElementById('message').textContent = 'Vous avez épuisé toutes vos tentatives. Le mot était : ' + word;
                                document.getElementById('submitGuess').disabled = true;
                            }
                        }
                    } else {
                        document.getElementById('message').textContent = data.message;
                    }
                });
        }
    </script>

</body>

</html>