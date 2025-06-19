document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('toggleUsersBtn');
    const usersSection = document.getElementById('usersSection');
    let usersVisible = typeof window.usersVisible !== 'undefined' ? window.usersVisible : false;
    if (toggleBtn && usersSection) {
        toggleBtn.textContent = usersVisible ? 'Cacher la gestion des utilisateurs' : 'Afficher la gestion des utilisateurs';
        toggleBtn.onclick = function() {
            usersVisible = !usersVisible;
            usersSection.style.display = usersVisible ? 'block' : 'none';
            toggleBtn.textContent = usersVisible ? 'Cacher la gestion des utilisateurs' : 'Afficher la gestion des utilisateurs';
        };
    }
    const toggleProductsBtn = document.getElementById('toggleProductsBtn');
    const productsSection = document.getElementById('productsSection');
    let productsVisible = typeof window.productsVisible !== 'undefined' ? window.productsVisible : false;
    if (toggleProductsBtn && productsSection) {
        toggleProductsBtn.textContent = productsVisible ? 'Cacher la gestion des produits' : 'Afficher la gestion des produits';
        toggleProductsBtn.onclick = function() {
            productsVisible = !productsVisible;
            productsSection.style.display = productsVisible ? 'block' : 'none';
            toggleProductsBtn.textContent = productsVisible ? 'Cacher la gestion des produits' : 'Afficher la gestion des produits';
        };
    }
    const toggleCategoriesBtn = document.getElementById('toggleCategoriesBtn');
    const categoriesSection = document.getElementById('categoriesSection');
    let categoriesVisible = typeof window.categoriesVisible !== 'undefined' ? window.categoriesVisible : false;
    if (toggleCategoriesBtn && categoriesSection) {
        toggleCategoriesBtn.textContent = categoriesVisible ? 'Cacher la gestion des catégories' : 'Afficher la gestion des catégories';
        toggleCategoriesBtn.onclick = function() {
            categoriesVisible = !categoriesVisible;
            categoriesSection.style.display = categoriesVisible ? 'block' : 'none';
            toggleCategoriesBtn.textContent = categoriesVisible ? 'Cacher la gestion des catégories' : 'Afficher la gestion des catégories';
        };
    }
});
