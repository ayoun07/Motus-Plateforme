<?php
session_start();
include('db.php');

$response = ['success' => false, 'result' => [], 'victory' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $guess = trim($_POST['guess'] ?? ''); // Supprimer les espaces superflus
    $word = trim($_POST['word'] ?? '');

    // Convertir les deux chaînes en majuscules pour une comparaison insensible à la casse
    $guess = strtoupper($guess);
    $word = strtoupper($word);

    // Vérifier la victoire
    if ($guess === $word) {
        $response['success'] = true;
        $response['victory'] = true;
        $response['result'] = array_fill(0, strlen($word), 'correct');
    } else {
        // Ajouter ici la logique pour vérifier les lettres présentes et leur position
        $response['success'] = true;
        $response['result'] = array_map(function ($char, $index) use ($word) {
            if ($word[$index] === $char) {
                return 'correct';
            } elseif (strpos($word, $char) !== false) {
                return 'present';
            } else {
                return 'absent';
            }
        }, str_split($guess), array_keys(str_split($guess)));
    }

    echo json_encode($response);
}
