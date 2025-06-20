<?php
// Page d'accueil après connexion
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit();
}

require_once '../db/db_connect.php';

$user = $_SESSION['user'];

// Récupérer tous les produits avec leurs catégories
try {
    $pdo = getPDO();
    $stmt = $pdo->prepare('
        SELECT p.*, c.name as category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        ORDER BY p.id ASC
    ');
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $products = [];
    $error_message = 'Erreur lors du chargement des produits: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil - SkinVault</title>
    <link rel="stylesheet" href="../css/home.css">
</head>
<body>
    <div class="home-container">
        <h1 class="site-title">SKINVAULT</h1>
        
        <div class="user-info">
            <span>Bienvenue, <?php echo htmlspecialchars($user['username']); ?> !</span>
            <a href="../index.php?logout=1" class="logout-btn">Déconnexion</a>
        </div>

        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <div class="products-grid">
            <?php if (empty($products)): ?>
                <div class="no-products">Aucun produit disponible pour le moment.</div>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <?php 
                            $image_path = '';
                            $image_exists = false;
                            
                            if (!empty($product['cover_image'])) {
                                // Essayer différents chemins possibles
                                $possible_paths = [
                                    '../' . $product['cover_image'],  // Chemin relatif depuis pages/
                                    '../images/' . $product['cover_image'],  // Dans le dossier images
                                    $product['cover_image']  // Chemin direct
                                ];
                                
                                foreach ($possible_paths as $path) {
                                    if (file_exists($path)) {
                                        $image_path = $path;
                                        $image_exists = true;
                                        break;
                                    }
                                }
                            }
                            
                            if ($image_exists): ?>
                                <img src="<?php echo htmlspecialchars($image_path); ?>" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>">
                            <?php else: ?>
                                <div class="image-placeholder">
                                    <span>Image non disponible</span>
                                    <?php if (!empty($product['cover_image'])): ?>
                                        <br><small><?php echo htmlspecialchars($product['cover_image']); ?></small>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="product-info">
                            <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                            <p class="product-category"><?php echo htmlspecialchars($product['category_name'] ?? 'Sans catégorie'); ?></p>
                            <p class="product-price"><?php echo number_format($product['price'], 2); ?> €</p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
