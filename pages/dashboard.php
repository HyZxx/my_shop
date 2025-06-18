<?php
// Dashboard admin
session_start();
if (!isset($_SESSION['user']) || empty($_SESSION['user']['admin'])) {
    header('Location: ../index.php');
    exit();
}
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - SkinVault</title>
</head>
<body>
    <h1>Bienvenue sur le Dashboard, <?php echo htmlspecialchars($user['username']); ?> (ADMIN)</h1>
    <a href="../index.php?logout=1">Déconnexion</a>
</body>
</html>
