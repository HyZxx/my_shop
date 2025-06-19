<?php
require_once '../db/dashboard_controller.php';
$showUsers = isset($_GET['showUsers']) && $_GET['showUsers'] == '1';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - SkinVault</title>
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
    <video class="video-bg" id="bgVideo" src="../images/background/poussiere.mp4" autoplay muted loop playsinline></video>
    <div class="dashboard-container">
        <h1 class="dashboard-title">SkinVault Admin</h1>
        <a href="../index.php?logout=1" style="position:absolute;top:24px;right:32px;z-index:5;color:#fff;">Déconnexion</a>
        <?php if ($message): ?>
            <div class="dashboard-message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <button id="toggleUsersBtn" style="margin: 24px auto 0 auto; display: block; background: #fff; color: #111; border: none; border-radius: 8px; padding: 12px 32px; font-size: 1.1em; font-weight: bold; cursor: pointer; box-shadow: 0 0 16px #fff;">Afficher la gestion des utilisateurs</button>
        <div id="usersSection" style="display: <?php echo $showUsers ? 'block' : 'none'; ?>; margin-top: 32px;">
            <form class="dashboard-form" method="post">
                <h2>Ajouter un utilisateur</h2>
                <label>Nom d'utilisateur : <input type="text" name="username" required></label><br>
                <label>Email : <input type="email" name="email" required></label><br>
                <label>Mot de passe : <input type="password" name="password" required></label><br>
                <label>Admin <input type="checkbox" name="admin"></label><br>
                <button type="submit" name="add_user">Ajouter</button>
            </form>
            <div class="dashboard-users">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                    <h2 style="margin: 0;">Utilisateurs</h2>
                    <form method="get" style="margin: 0;">
                        <input type="hidden" name="showUsers" value="1">
                        <label for="limit" style="color:#fff; font-weight:normal;">Afficher
                            <select name="limit" id="limit" onchange="this.form.submit()" style="margin-left:4px;">
                                <option value="1" <?php if ((isset($_GET['limit']) && $_GET['limit']==1)) echo 'selected'; ?>>1</option>
                                <option value="10" <?php if ((isset($_GET['limit']) && $_GET['limit']==10)) echo 'selected'; ?>>10</option>
                                <option value="25" <?php if ((isset($_GET['limit']) && $_GET['limit']==25)) echo 'selected'; ?>>25</option>
                                <option value="all" <?php if ((isset($_GET['limit']) && $_GET['limit']==='all')) echo 'selected'; ?>>Tous</option>
                            </select>
                        </label>
                    </form>
                </div>
                <table>
                    <tr><th>ID</th><th>Nom</th><th>Email</th><th>Admin</th><th>Actions</th></tr>
                    <?php foreach ($users as $u): ?>
                    <tr>
                        <form method="post">
                            <td><?php echo $u['id']; ?><input type="hidden" name="id" value="<?php echo $u['id']; ?>"></td>
                            <td><input type="text" name="username" value="<?php echo htmlspecialchars($u['username']); ?>" required></td>
                            <td><input type="email" name="email" value="<?php echo htmlspecialchars($u['email']); ?>" required></td>
                            <td><input type="checkbox" name="admin" <?php if ($u['admin']) echo 'checked'; ?>></td>
                            <td>
                                <input type="password" name="password" placeholder="Nouveau mot de passe">
                                <button type="submit" name="edit_user">Modifier</button>
                                <button type="submit" name="delete_user" onclick="return confirm('Supprimer cet utilisateur ?')">Supprimer</button>
                            </td>
                        </form>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
        <script>
            window.usersVisible = <?php echo $showUsers ? 'true' : 'false'; ?>;
        </script>
        <script src="../js/dashboard.js"></script>
    </div>
</body>
</html>
