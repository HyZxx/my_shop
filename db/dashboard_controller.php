<?php
require_once '../db/db_connect.php';
session_start();
if (!isset($_SESSION['user']) || empty($_SESSION['user']['admin'])) {
    header('Location: ../index.php');
    exit();
}
$pdo = getPDO();
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $admin = isset($_POST['admin']) ? 1 : 0;
    if ($username && $email && $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO users (username, password, email, admin) VALUES (?, ?, ?, ?)');
        $stmt->execute([$username, $hash, $email, $admin]);
        $message = 'Utilisateur ajouté !';
    } else {
        $message = 'Champs manquants.';
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_user'])) {
    $id = intval($_POST['id']);
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $admin = isset($_POST['admin']) ? 1 : 0;
    if ($username && $email) {
        $sql = 'UPDATE users SET username=?, email=?, admin=?';
        $params = [$username, $email, $admin];
        if (!empty($_POST['password'])) {
            $sql .= ', password=?';
            $params[] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }
        $sql .= ' WHERE id=?';
        $params[] = $id;
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $message = 'Utilisateur modifié !';
    } else {
        $message = 'Champs manquants.';
    }
}
if (isset($_POST['delete_user'])) {
    $id = intval($_POST['id']);
    $stmt = $pdo->prepare('DELETE FROM users WHERE id=?');
    $stmt->execute([$id]);
    $message = 'Utilisateur supprimé !';
}
$limit = 10;
if (isset($_GET['limit'])) {
    if ($_GET['limit'] === 'all') {
        $limit = null;
    } elseif (is_numeric($_GET['limit'])) {
        $limit = intval($_GET['limit']);
    }
}
if ($limit) {
    $users = $pdo->query('SELECT * FROM users LIMIT ' . $limit)->fetchAll();
} else {
    $users = $pdo->query('SELECT * FROM users')->fetchAll();
}
