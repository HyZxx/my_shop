document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('toggleUsersBtn');
    const usersSection = document.getElementById('usersSection');
    const toggleProductsBtn = document.getElementById('toggleProductsBtn');
    const productsSection = document.getElementById('productsSection');
    const toggleCategoriesBtn = document.getElementById('toggleCategoriesBtn');
    const categoriesSection = document.getElementById('categoriesSection');

    function hideAllSections() {
        usersSection.style.display = 'none';
        productsSection.style.display = 'none';
        categoriesSection.style.display = 'none';
    }

    function resetAllBtns() {
        toggleBtn.textContent = 'Afficher la gestion des utilisateurs';
        toggleProductsBtn.textContent = 'Afficher la gestion des produits';
        toggleCategoriesBtn.textContent = 'Afficher la gestion des catégories';
    }

    toggleBtn.onclick = function() {
        if (usersSection.style.display === 'block') {
            usersSection.style.display = 'none';
            toggleBtn.textContent = 'Afficher la gestion des utilisateurs';
        } else {
            hideAllSections();
            resetAllBtns();
            usersSection.style.display = 'block';
            toggleBtn.textContent = 'Cacher la gestion des utilisateurs';
        }
    };
    toggleProductsBtn.onclick = function() {
        if (productsSection.style.display === 'block') {
            productsSection.style.display = 'none';
            toggleProductsBtn.textContent = 'Afficher la gestion des produits';
        } else {
            hideAllSections();
            resetAllBtns();
            productsSection.style.display = 'block';
            toggleProductsBtn.textContent = 'Cacher la gestion des produits';
        }
    };
    toggleCategoriesBtn.onclick = function() {
        if (categoriesSection.style.display === 'block') {
            categoriesSection.style.display = 'none';
            toggleCategoriesBtn.textContent = 'Afficher la gestion des catégories';
        } else {
            hideAllSections();
            resetAllBtns();
            categoriesSection.style.display = 'block';
            toggleCategoriesBtn.textContent = 'Cacher la gestion des catégories';
        }
    };
});
