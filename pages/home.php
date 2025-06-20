<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit();
}

require_once '../db/db_connect.php';

$user = $_SESSION['user'];

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
            <div class="search-container">
                <div class="search-box">
                    <svg class="search-icon" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                    </svg>
                    <input type="text" id="searchInput" placeholder="Rechercher par nom ou catégorie..." onkeyup="filterProducts()">
                    <button class="search-clear" id="clearSearch" onclick="clearSearch()" style="display: none;">&times;</button>
                </div>
            </div>
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
                    <div class="product-card" onclick="openProductPopup(<?php echo htmlspecialchars(json_encode($product)); ?>, '<?php echo $popup_image_exists ? htmlspecialchars($popup_image_path) : ''; ?>')">
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
    
    <!-- Popup produit -->
    <div id="productPopup" class="popup-overlay">
        <div class="popup-content">
            <button class="popup-close" onclick="closeProductPopup()">&times;</button>
            <div class="popup-body">
                <div class="popup-image">
                    <img id="popupImage" src="" alt="">
                    <div id="popupImagePlaceholder" class="popup-image-placeholder" style="display: none;">
                        <span>Image non disponible</span>
                    </div>
                </div>
                <div class="popup-details">
                    <h2 id="popupName"></h2>
                    <p class="popup-category">Catégorie: <span id="popupCategory"></span></p>
                    <p class="popup-price" id="popupPrice"></p>
                    <div class="popup-description">
                        <h3>Description</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function openProductPopup(product, imagePath) {
            const popup = document.getElementById('productPopup');
            const popupImage = document.getElementById('popupImage');
            const popupImagePlaceholder = document.getElementById('popupImagePlaceholder');
            const popupName = document.getElementById('popupName');
            const popupCategory = document.getElementById('popupCategory');
            const popupPrice = document.getElementById('popupPrice');
            
            popupName.textContent = product.name;
            popupCategory.textContent = product.category_name || 'Sans catégorie';
            popupPrice.textContent = parseFloat(product.price).toFixed(2) + ' €';
            
            if (imagePath && imagePath.trim() !== '') {
                popupImage.src = imagePath;
                popupImage.alt = product.name;
                popupImage.style.display = 'block';
                popupImagePlaceholder.style.display = 'none';
            } else {
                popupImage.style.display = 'none';
                popupImagePlaceholder.style.display = 'flex';
            }
            
            popup.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
        
        function closeProductPopup() {
            const popup = document.getElementById('productPopup');
            popup.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        
        function filterProducts() {
            const searchInput = document.getElementById('searchInput');
            const clearButton = document.getElementById('clearSearch');
            const searchTerm = searchInput.value.toLowerCase().trim();
            const productCards = document.querySelectorAll('.product-card');
            const noProductsDiv = document.querySelector('.no-products');
            const productsGrid = document.querySelector('.products-grid');
            
            if (searchTerm.length > 0) {
                clearButton.style.display = 'block';
            } else {
                clearButton.style.display = 'none';
            }
            
            let visibleCount = 0;
            const visibleCards = [];
            
            productCards.forEach(card => {
                const productName = card.querySelector('.product-name').textContent.toLowerCase();
                const productCategory = card.querySelector('.product-category').textContent.toLowerCase();
                
                if (searchTerm === '' || 
                    productName.startsWith(searchTerm) || 
                    productCategory.startsWith(searchTerm)) {
                    visibleCards.push(card);
                    visibleCount++;
                }
            });
            
            if (searchTerm === '') {
                productCards.forEach(card => {
                    card.style.order = '';
                    card.style.display = 'flex';
                    card.style.opacity = '0';
                    setTimeout(() => {
                        card.style.opacity = '1';
                    }, 10);
                });
            } else {
                productCards.forEach(card => {
                    card.style.display = 'none';
                    card.style.opacity = '0';
                });
                
                visibleCards.forEach((card, index) => {
                    card.style.display = 'flex';
                    card.style.order = index;
                    setTimeout(() => {
                        card.style.opacity = '1';
                    }, index * 50 + 10);
                });
            }
            
            if (noProductsDiv) {
                if (searchTerm.length > 0 && visibleCount === 0) {
                    noProductsDiv.style.display = 'block';
                    noProductsDiv.textContent = 'Aucun produit trouvé commençant par "' + searchInput.value + '"';
                } else {
                    noProductsDiv.style.display = 'none';
                }
            }
        }
        
        function clearSearch() {
            const searchInput = document.getElementById('searchInput');
            const clearButton = document.getElementById('clearSearch');
            
            searchInput.value = '';
            clearButton.style.display = 'none';
            
            const productCards = document.querySelectorAll('.product-card');
            productCards.forEach(card => {
                card.style.opacity = '0';
            });
            
            setTimeout(() => {
                filterProducts();
            }, 150);
            
            searchInput.focus();
        }
        
        document.getElementById('productPopup').addEventListener('click', function(e) {
            if (e.target === this) {
                closeProductPopup();
            }
        });
        
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeProductPopup();
            }
        });
    </script>
</body>
</html>
