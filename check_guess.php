<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $guess = strtoupper($_POST['guess']);
    $word = strtoupper($_POST['word']);
    $response = ['result' => [], 'victory' => false];

    for ($i = 0; $i < strlen($word); $i++) {
        if ($guess[$i] === $word[$i]) {
            $response['result'][$i] = 'correct';
        } elseif (strpos($word, $guess[$i]) !== false) {
            $response['result'][$i] = 'present';
        } else {
            $response['result'][$i] = 'absent';
        }
    }

    if ($guess === $word) {
        $response['victory'] = true;
    }

    echo json_encode(['success' => true] + $response);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Requête invalide.']);
exit;
