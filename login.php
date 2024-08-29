<?php
include('db.php');

$response = array('success' => false);

if (isset($_POST['username'], $_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $response['success'] = true;
    } else {
        $response['message'] = 'Nom d\'utilisateur ou mot de passe incorrect.';
    }
}

echo json_encode($response);
