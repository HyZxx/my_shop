<?php
require_once 'db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        $pdo = getPDO();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'admin' => $user['admin']
            ];
            echo json_encode(['success' => true, 'admin' => $user['admin']]);
            exit;
        }
        echo json_encode(['success' => false, 'message' => 'Identifiants invalides.']);
        exit;
    }
    echo json_encode(['success' => false, 'message' => 'Champs manquants.']);
    exit;
}
echo json_encode(['success' => false, 'message' => 'Mauvaise requête.']);
