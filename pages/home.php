<?php
// Page d'accueil après connexion
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit();
}
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil - SkinVault</title>
</head>
<body>
    <h1>Bienvenue sur SkinVault, <?php echo htmlspecialchars($user['username']); ?> !</h1>
    <a href="../index.php?logout=1">Déconnexion</a>
</body>
</html>
