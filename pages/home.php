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
        
        <!-- Footer -->
        <footer class="footer">
            <div class="footer-content">
                <div class="footer-left">
                    <video class="footer-video" autoplay muted loop>
                        <source src="../images/background/pion.mp4" type="video/mp4">
                    </video>
                </div>
                <div class="footer-right">
                    <p class="footer-text">© SkinVault Inc. 2025. Not affiliated with Valve Corp.</p>
                    <a href="https://github.com/HyZxx/my_shop" target="_blank" class="github-link">
                        <svg class="github-icon" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 0C5.374 0 0 5.373 0 12 0 17.302 3.438 21.8 8.207 23.387c.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23A11.509 11.509 0 0112 5.803c1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576C20.566 21.797 24 17.3 24 12c0-6.627-5.373-12-12-12z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
