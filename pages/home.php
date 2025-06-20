<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit();
}

require_once '../db/db_connect.php';

$user = $_SESSION['user'];

// Gestion de la recherche
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';

// Gestion du popup produit
$popup_product_id = isset($_GET['product']) ? (int)$_GET['product'] : 0;
$popup_product = null;

try {
    $pdo = getPDO();
    
    // Si un produit est sélectionné pour le popup, le récupérer
    if ($popup_product_id > 0) {
        $stmt = $pdo->prepare('
            SELECT p.*, c.name as category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.id = ?
        ');
        $stmt->execute([$popup_product_id]);
        $popup_product = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Récupérer tous les produits avec filtrage par recherche
    $sql = '
        SELECT p.*, c.name as category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id
    ';
    
    $params = [];
    if (!empty($search_term)) {
        $sql .= ' WHERE (p.name LIKE ? OR c.name LIKE ?)';
        $search_param = $search_term . '%';
        $params = [$search_param, $search_param];
    }
    
    $sql .= ' ORDER BY p.id ASC';
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
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
            <div class="search-container">
                <form method="GET" action="" class="search-form">
                    <?php if ($popup_product_id > 0): ?>
                        <input type="hidden" name="product" value="<?php echo $popup_product_id; ?>">
                    <?php endif; ?>
                    <div class="search-box">
                        <svg class="search-icon" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                        </svg>
                        <input type="text" name="search" placeholder="Rechercher par nom ou catégorie..." value="<?php echo htmlspecialchars($search_term); ?>">
                        <?php if (!empty($search_term)): ?>
                            <a href="?<?php echo $popup_product_id > 0 ? 'product=' . $popup_product_id : ''; ?>" class="search-clear">&times;</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
            <a href="../index.php?logout=1" class="logout-btn">Déconnexion</a>
        </div>

        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <div class="products-grid">
            <?php if (empty($products)): ?>
                <?php if (!empty($search_term)): ?>
                    <div class="no-products">Aucun produit trouvé commençant par "<?php echo htmlspecialchars($search_term); ?>"</div>
                <?php else: ?>
                    <div class="no-products">Aucun produit disponible pour le moment.</div>
                <?php endif; ?>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <?php 
                    $card_image_path = '';
                    $card_image_exists = false;
                    
                    if (!empty($product['cover_image'])) {
                        $possible_paths = [
                            '../' . $product['cover_image'],
                            '../images/' . $product['cover_image'],
                            $product['cover_image']
                        ];
                        
                        foreach ($possible_paths as $path) {
                            if (file_exists($path)) {
                                $card_image_path = $path;
                                $card_image_exists = true;
                                break;
                            }
                        }
                    }
                    
                    $popup_image_path = '';
                    $popup_image_exists = false;
                    
                    if (!empty($product['image'])) {
                        $possible_paths = [
                            '../' . $product['image'],
                            '../images/' . $product['image'],
                            $product['image']
                        ];
                        
                        foreach ($possible_paths as $path) {
                            if (file_exists($path)) {
                                $popup_image_path = $path;
                                $popup_image_exists = true;
                                break;
                            }
                        }
                    }
                    
                    if (!$popup_image_exists && $card_image_exists) {
                        $popup_image_path = $card_image_path;
                        $popup_image_exists = true;
                    }
                    ?>
                    <a href="?product=<?php echo $product['id']; ?><?php echo !empty($search_term) ? '&search=' . urlencode($search_term) : ''; ?>" class="product-card">
                        <div class="product-image">
                            <?php if ($card_image_exists): ?>
                                <img src="<?php echo htmlspecialchars($card_image_path); ?>" 
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
                    </a>
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
    
    <!-- Popup produit -->
    <?php if ($popup_product): ?>
        <?php 
        // Gestion des images pour le popup
        $popup_image_path = '';
        $popup_image_exists = false;
        
        if (!empty($popup_product['image'])) {
            $possible_paths = [
                '../' . $popup_product['image'],
                '../images/' . $popup_product['image'],
                $popup_product['image']
            ];
            
            foreach ($possible_paths as $path) {
                if (file_exists($path)) {
                    $popup_image_path = $path;
                    $popup_image_exists = true;
                    break;
                }
            }
        }
        
        // Fallback vers cover_image
        if (!$popup_image_exists && !empty($popup_product['cover_image'])) {
            $possible_paths = [
                '../' . $popup_product['cover_image'],
                '../images/' . $popup_product['cover_image'],
                $popup_product['cover_image']
            ];
            
            foreach ($possible_paths as $path) {
                if (file_exists($path)) {
                    $popup_image_path = $path;
                    $popup_image_exists = true;
                    break;
                }
            }
        }
        ?>
        <div class="popup-overlay" style="display: flex;">
            <div class="popup-content">
                <a href="?<?php echo !empty($search_term) ? 'search=' . urlencode($search_term) : ''; ?>" class="popup-close">&times;</a>
                <div class="popup-body">
                    <div class="popup-image">
                        <?php if ($popup_image_exists): ?>
                            <img src="<?php echo htmlspecialchars($popup_image_path); ?>" alt="<?php echo htmlspecialchars($popup_product['name']); ?>">
                        <?php else: ?>
                            <div class="popup-image-placeholder">
                                <span>Image non disponible</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="popup-details">
                        <h2><?php echo htmlspecialchars($popup_product['name']); ?></h2>
                        <p class="popup-category">Catégorie: <span><?php echo htmlspecialchars($popup_product['category_name'] ?? 'Sans catégorie'); ?></span></p>
                        <p class="popup-price"><?php echo number_format($popup_product['price'], 2); ?> €</p>
                        <div class="popup-description">
                            <h3>Description</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                        </div>
                        <button class="buy-button">Acheter</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</body>
</html>
