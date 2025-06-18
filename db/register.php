<?php
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $email && $password) {
        try {
            $pdo = getPDO();
            $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
            $stmt->execute([$username, $email]);
            if ($stmt->fetch()) {
                echo json_encode(['success' => false, 'message' => "Nom d'utilisateur ou email déjà utilisé."]);
                exit;
            }
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (username, password, email, admin) VALUES (?, ?, ?, 0)');
            $stmt->execute([$username, $hash, $email]);
            echo json_encode(['success' => true]);
            exit;
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Erreur PDO: ' . $e->getMessage()]);
            exit;
        }
    }
    echo json_encode(['success' => false, 'message' => 'Champs manquants.']);
    exit;
}
echo json_encode(['success' => false, 'message' => 'Mauvaise requête.']);
