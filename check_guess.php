<?php
header('Content-Type: application/json');

$response = array('success' => false);

if (isset($_POST['guess'], $_POST['word'])) {
    $guess = strtoupper($_POST['guess']);
    $word = strtoupper($_POST['word']);
    $wordLength = strlen($word);
    $result = array_fill(0, $wordLength, 'absent');

    for ($i = 0; $i < $wordLength; $i++) {
        if ($guess[$i] === $word[$i]) {
            $result[$i] = 'correct';
        } elseif (strpos($word, $guess[$i]) !== false) {
            $result[$i] = 'present';
        }
    }

    $response['success'] = true;
    $response['result'] = $result;
    $response['victory'] = $guess === $word;
}

echo json_encode($response);
