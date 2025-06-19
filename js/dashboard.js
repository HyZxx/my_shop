document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('toggleUsersBtn');
    const usersSection = document.getElementById('usersSection');
    const toggleProductsBtn = document.getElementById('toggleProductsBtn');
    const productsSection = document.getElementById('productsSection');
    const toggleCategoriesBtn = document.getElementById('toggleCategoriesBtn');
    const categoriesSection = document.getElementById('categoriesSection');
    const toggleAddBtn = document.getElementById('toggleAddBtn');
    const addUserForm = document.getElementById('addUserForm');
    const addProductForm = document.getElementById('addProductForm');
    const addCategoryForm = document.getElementById('addCategoryForm');

    function hideAllSections() {
        usersSection.style.display = 'none';
        productsSection.style.display = 'none';
        categoriesSection.style.display = 'none';
        toggleAddBtn.style.display = 'none';
        addUserForm.style.display = 'none';
        addProductForm.style.display = 'none';
        addCategoryForm.style.display = 'none';
    }

    function resetAllBtns() {
        toggleBtn.textContent = 'Afficher la gestion des utilisateurs';
        toggleProductsBtn.textContent = 'Afficher la gestion des produits';
        toggleCategoriesBtn.textContent = 'Afficher la gestion des catégories';
        toggleAddBtn.textContent = 'Afficher l\'ajout';
    }

    toggleBtn.onclick = function() {
        if (usersSection.style.display === 'block') {
            usersSection.style.display = 'none';
            toggleBtn.textContent = 'Afficher la gestion des utilisateurs';
            toggleAddBtn.style.display = 'none';
            addUserForm.style.display = 'none';
        } else {
            hideAllSections();
            resetAllBtns();
            usersSection.style.display = 'block';
            toggleBtn.textContent = 'Cacher la gestion des utilisateurs';
            toggleAddBtn.style.display = 'inline-block';
            toggleAddBtn.textContent = addUserForm.style.display === 'block' ? 'Cacher l\'ajout' : 'Afficher l\'ajout';
        }
    };
    toggleProductsBtn.onclick = function() {
        if (productsSection.style.display === 'block') {
            productsSection.style.display = 'none';
            toggleProductsBtn.textContent = 'Afficher la gestion des produits';
            toggleAddBtn.style.display = 'none';
            addProductForm.style.display = 'none';
        } else {
            hideAllSections();
            resetAllBtns();
            productsSection.style.display = 'block';
            toggleProductsBtn.textContent = 'Cacher la gestion des produits';
            toggleAddBtn.style.display = 'inline-block';
            toggleAddBtn.textContent = addProductForm.style.display === 'block' ? 'Cacher l\'ajout' : 'Afficher l\'ajout';
        }
    };
    toggleCategoriesBtn.onclick = function() {
        if (categoriesSection.style.display === 'block') {
            categoriesSection.style.display = 'none';
            toggleCategoriesBtn.textContent = 'Afficher la gestion des catégories';
            toggleAddBtn.style.display = 'none';
            addCategoryForm.style.display = 'none';
        } else {
            hideAllSections();
            resetAllBtns();
            categoriesSection.style.display = 'block';
            toggleCategoriesBtn.textContent = 'Cacher la gestion des catégories';
            toggleAddBtn.style.display = 'inline-block';
            toggleAddBtn.textContent = addCategoryForm.style.display === 'block' ? 'Cacher l\'ajout' : 'Afficher l\'ajout';
        }
    };
    toggleAddBtn.onclick = function() {
        if (usersSection.style.display === 'block') {
            if (addUserForm.style.display === 'block') {
                addUserForm.style.display = 'none';
                toggleAddBtn.textContent = 'Afficher l\'ajout';
            } else {
                addUserForm.style.display = 'block';
                toggleAddBtn.textContent = 'Cacher l\'ajout';
            }
        } else if (productsSection.style.display === 'block') {
            if (addProductForm.style.display === 'block') {
                addProductForm.style.display = 'none';
                toggleAddBtn.textContent = 'Afficher l\'ajout';
            } else {
                addProductForm.style.display = 'block';
                toggleAddBtn.textContent = 'Cacher l\'ajout';
            }
        } else if (categoriesSection.style.display === 'block') {
            if (addCategoryForm.style.display === 'block') {
                addCategoryForm.style.display = 'none';
                toggleAddBtn.textContent = 'Afficher l\'ajout';
            } else {
                addCategoryForm.style.display = 'block';
                toggleAddBtn.textContent = 'Cacher l\'ajout';
            }
        }
    };
});
