<?php
require_once '../db/dashboard_controller.php';
$showUsers = isset($_GET['showUsers']) && $_GET['showUsers'] == '1' && $_SERVER['REQUEST_METHOD'] !== 'POST';
$showProducts = (isset($_GET['showProducts']) && $_GET['showProducts'] == '1') || (isset($_POST['add_product']) || isset($_POST['edit_product']) || isset($_POST['delete_product']));
$showCategories = (isset($_GET['showCategories']) && $_GET['showCategories'] == '1') || (isset($_POST['add_category']) || isset($_POST['edit_category']) || isset($_POST['delete_category']));
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
    <video class="video-bg" id="bgVideo" src="../images/background/nuage.mp4" autoplay muted loop playsinline></video>
    <div class="dashboard-container">
        <h1 class="dashboard-title">SkinVault Admin</h1>
        <a href="../index.php?logout=1" style="position:absolute;top:24px;right:32px;z-index:5;color:#fff;">Déconnexion</a>
        <a href="home.php" style="position:absolute;top:60px;right:32px;z-index:5;color:#fff;text-decoration:underline;">Visualisation utilisateur</a>
        <?php if ($message): ?>
            <div class="dashboard-message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <div class="dashboard-btns">
            <button id="toggleUsersBtn" class="dashboard-toggle-btn">Afficher la gestion des utilisateurs</button>
            <button id="toggleProductsBtn" class="dashboard-toggle-btn">Afficher la gestion des produits</button>
            <button id="toggleCategoriesBtn" class="dashboard-toggle-btn">Afficher la gestion des catégories</button>
            <button id="toggleAddBtn" class="dashboard-toggle-btn" style="display:none;">Afficher l'ajout</button>
        </div>
        <div id="usersSection" style="display: <?php echo $showUsers ? 'block' : 'none'; ?>; margin-top: 32px;">
            <div class="dashboard-flex-forms">
                <form class="dashboard-form" id="addUserForm" method="post" style="display:none;">
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
        </div>
        <div id="productsSection" style="display: <?php echo $showProducts ? 'block' : 'none'; ?>; margin-top: 32px;">
            <div class="dashboard-flex-forms">
                <form class="dashboard-form" id="addProductForm" method="post" style="display:none;">
                    <h2>Ajouter un produit</h2>
                    <label>Nom : <input type="text" name="name" required></label><br>
                    <label>Prix : <input type="number" name="price" required></label><br>
                    <label>Catégorie : <select name="category_id" required>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                        <?php endforeach; ?>
                    </select></label><br>
                    <label>Image principale : <input type="text" name="image" placeholder="URL ou chemin"></label><br>
                    <label>Image de couverture : <input type="text" name="cover_image" placeholder="URL ou chemin"></label><br>
                    <button type="submit" name="add_product">Ajouter</button>
                </form>
                <div class="dashboard-users">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                        <h2 style="margin: 0;">Produits</h2>
                        <form method="get" style="margin: 0;">
                            <input type="hidden" name="showProducts" value="1">
                            <label for="product_limit" style="color:#fff; font-weight:normal;">Afficher
                                <select name="product_limit" id="product_limit" onchange="this.form.submit()" style="margin-left:4px;">
                                    <option value="1" <?php if ((isset($_GET['product_limit']) && $_GET['product_limit']==1)) echo 'selected'; ?>>1</option>
                                    <option value="10" <?php if ((isset($_GET['product_limit']) && $_GET['product_limit']==10)) echo 'selected'; ?>>10</option>
                                    <option value="25" <?php if ((isset($_GET['product_limit']) && $_GET['product_limit']==25)) echo 'selected'; ?>>25</option>
                                    <option value="all" <?php if ((isset($_GET['product_limit']) && $_GET['product_limit']==='all')) echo 'selected'; ?>>Tous</option>
                                </select>
                            </label>
                        </form>
                    </div>
                    <table>
                        <tr><th>ID</th><th>Nom</th><th>Prix</th><th>Catégorie</th><th>Image</th><th>Couverture</th><th>Actions</th></tr>
                        <?php foreach ($products as $p): ?>
                        <tr>
                            <form method="post">
                                <td><?php echo $p['id']; ?><input type="hidden" name="id" value="<?php echo $p['id']; ?>"></td>
                                <td><input type="text" name="name" value="<?php echo htmlspecialchars($p['name']); ?>" required></td>
                                <td><input type="number" name="price" value="<?php echo $p['price']; ?>" required></td>
                                <td><select name="category_id" required>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo $cat['id']; ?>" <?php if ($cat['id'] == $p['category_id']) echo 'selected'; ?>><?php echo htmlspecialchars($cat['name']); ?></option>
                                    <?php endforeach; ?>
                                </select></td>
                                <td><input type="text" name="image" value="<?php echo htmlspecialchars($p['image']); ?>" placeholder="URL ou chemin"></td>
                                <td><input type="text" name="cover_image" value="<?php echo htmlspecialchars($p['cover_image']); ?>" placeholder="URL ou chemin"></td>
                                <td>
                                    <button type="submit" name="edit_product">Modifier</button>
                                    <button type="submit" name="delete_product" onclick="return confirm('Supprimer ce produit ?')">Supprimer</button>
                                </td>
                            </form>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
        <div id="categoriesSection" style="display: <?php echo $showCategories ? 'block' : 'none'; ?>; margin-top: 32px;">
            <div class="dashboard-flex-forms">
                <form class="dashboard-form" id="addCategoryForm" method="post" style="display:none;">
                    <h2>Ajouter une catégorie</h2>
                    <label>Nom : <input type="text" name="name" required></label><br>
                    <button type="submit" name="add_category">Ajouter</button>
                </form>
                <div class="dashboard-users">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                        <h2 style="margin: 0;">Catégories</h2>
                        <form method="get" style="margin: 0;">
                            <input type="hidden" name="showCategories" value="1">
                            <label for="category_limit" style="color:#fff; font-weight:normal;">Afficher
                                <select name="category_limit" id="category_limit" onchange="this.form.submit()" style="margin-left:4px;">
                                    <option value="1" <?php if ((isset($_GET['category_limit']) && $_GET['category_limit']==1)) echo 'selected'; ?>>1</option>
                                    <option value="10" <?php if ((isset($_GET['category_limit']) && $_GET['category_limit']==10)) echo 'selected'; ?>>10</option>
                                    <option value="25" <?php if ((isset($_GET['category_limit']) && $_GET['category_limit']==25)) echo 'selected'; ?>>25</option>
                                    <option value="all" <?php if ((isset($_GET['category_limit']) && $_GET['category_limit']==='all')) echo 'selected'; ?>>Tous</option>
                                </select>
                            </label>
                        </form>
                    </div>
                    <table>
                        <tr><th>ID</th><th>Nom</th><th>Actions</th></tr>
                        <?php foreach ($categoriesList as $c): ?>
                        <tr>
                            <form method="post">
                                <td><?php echo $c['id']; ?><input type="hidden" name="id" value="<?php echo $c['id']; ?>"></td>
                                <td><input type="text" name="name" value="<?php echo htmlspecialchars($c['name']); ?>" required></td>
                                <td>
                                    <button type="submit" name="edit_category">Modifier</button>
                                    <button type="submit" name="delete_category" onclick="return confirm('Supprimer cette catégorie ?')">Supprimer</button>
                                </td>
                            </form>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
        <script>
            window.usersVisible = <?php echo $showUsers ? 'true' : 'false'; ?>;
            window.productsVisible = <?php echo $showProducts ? 'true' : 'false'; ?>;
            window.categoriesVisible = <?php echo $showCategories ? 'true' : 'false'; ?>;
        </script>
        <script src="../js/dashboard.js"></script>
    </div>
</body>
</html>
