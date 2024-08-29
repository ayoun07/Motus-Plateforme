<?php
include('db.php');

$response = array('success' => false);

if (isset($_POST['username'], $_POST['email'], $_POST['password'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
    try {
        $stmt->execute([$username, $email, $password]);
        $response['success'] = true;
    } catch (Exception $e) {
        $response['message'] = 'Erreur lors de l\'inscription: ' . $e->getMessage();
    }
}

echo json_encode($response);
