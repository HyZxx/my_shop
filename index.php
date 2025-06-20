<?php
session_start();

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}

if (isset($_SESSION['user'])) {
    if ($_SESSION['user']['admin'] == 1) {
        header('Location: pages/dashboard.php');
    } else {
        header('Location: pages/home.php');
    }
    exit;
}

$step = $_GET['step'] ?? 'intro';
$form_type = $_GET['form'] ?? 'signup';
$error_msg = '';
$success_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'register') {
    require_once 'db/db_connect.php';
    
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $email && $password) {
        try {
            $pdo = getPDO();
            $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
            $stmt->execute([$username, $email]);
            if ($stmt->fetch()) {
                $error_msg = "Nom d'utilisateur ou email déjà utilisé.";
                $step = 'forms';
                $form_type = 'signup';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare('INSERT INTO users (username, password, email, admin) VALUES (?, ?, ?, 0)');
                $stmt->execute([$username, $hash, $email]);
                $success_msg = 'Inscription réussie ! Vous pouvez maintenant vous connecter.';
                $step = 'forms';
                $form_type = 'login';
            }
        } catch (Exception $e) {
            $error_msg = 'Erreur de base de données: ' . $e->getMessage();
            $step = 'forms';
            $form_type = 'signup';
        }
    } else {
        $error_msg = 'Tous les champs sont requis.';
        $step = 'forms';
        $form_type = 'signup';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    require_once 'db/db_connect.php';
    
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        try {
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
                if ($user['admin'] == 1) {
                    header('Location: pages/dashboard.php');
                } else {
                    header('Location: pages/home.php');
                }
                exit;
            } else {
                $error_msg = 'Identifiants invalides.';
                $step = 'forms';
                $form_type = 'login';
            }
        } catch (Exception $e) {
            $error_msg = 'Erreur de base de données: ' . $e->getMessage();
            $step = 'forms';
            $form_type = 'login';
        }
    } else {
        $error_msg = 'Nom d\'utilisateur et mot de passe requis.';
        $step = 'forms';
        $form_type = 'login';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>SkinVault</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body class="<?= $step === 'forms' ? 'forms-page' : 'intro-page' ?>">
    <?php if ($step === 'intro'): ?>
        <span class="site-title">SKINVAULT</span>
        <video class="video-bg" src="images/background/metro.mp4" autoplay muted playsinline preload="metadata"></video>
        <div class="fade-overlay"></div>
        <div class="blur-overlay"></div>
        <a href="?step=forms" class="enter-btn">Start Shopping</a>
    
    <?php else: ?>
        <!-- Vidéo avec autoplay retardé pour simuluer une continuation -->
        <video class="video-bg forms-video" src="images/background/metro.mp4" autoplay muted loop preload="auto"></video>
        <div class="fade-overlay"></div>
        <div class="blur-overlay"></div>
        
        <div class="form-container slide-in" style="display: flex;">
            <div class="form-header">
                <a href="?step=forms&form=signup" class="form-tab-btn <?= $form_type === 'signup' ? 'active' : '' ?>">INSCRIPTION</a>
                <a href="?step=forms&form=login" class="form-tab-btn <?= $form_type === 'login' ? 'active' : '' ?>">CONNEXION</a>
            </div>
            
            <?php if ($form_type === 'signup'): ?>
                <form method="POST" class="form-fields">
                    <input type="hidden" name="action" value="register">
                    <?php if ($error_msg): ?>
                        <div class="form-error"><?= htmlspecialchars($error_msg) ?></div>
                    <?php endif; ?>
                    <input type="text" name="username" placeholder="Nom d'utilisateur" required 
                           value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                    <input type="email" name="email" placeholder="Email" required 
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    <input type="password" name="password" placeholder="Mot de passe" required>
                    <button type="submit">S'inscrire</button>
                </form>
            
            <?php else: ?>
                <form method="POST" class="form-fields">
                    <input type="hidden" name="action" value="login">
                    <?php if ($error_msg): ?>
                        <div class="form-error"><?= htmlspecialchars($error_msg) ?></div>
                    <?php endif; ?>
                    <?php if ($success_msg): ?>
                        <div class="form-success"><?= htmlspecialchars($success_msg) ?></div>
                    <?php endif; ?>
                    <input type="text" name="username" placeholder="Nom d'utilisateur" required 
                           value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                    <input type="password" name="password" placeholder="Mot de passe" required>
                    <button type="submit">Se connecter</button>
                </form>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</body>
</html>
