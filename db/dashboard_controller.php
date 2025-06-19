<?php
require_once '../db/db_connect.php';
session_start();
if (!isset($_SESSION['user']) || empty($_SESSION['user']['admin'])) {
    header('Location: ../index.php');
    exit();
}
$pdo = getPDO();
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $admin = isset($_POST['admin']) ? 1 : 0;
    if ($username && $email && $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO users (username, password, email, admin) VALUES (?, ?, ?, ?)');
        $stmt->execute([$username, $hash, $email, $admin]);
        $message = 'Utilisateur ajouté !';
    } else {
        $message = 'Champs manquants.';
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_user'])) {
    $id = intval($_POST['id']);
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $admin = isset($_POST['admin']) ? 1 : 0;
    if ($username && $email) {
        $sql = 'UPDATE users SET username=?, email=?, admin=?';
        $params = [$username, $email, $admin];
        if (!empty($_POST['password'])) {
            $sql .= ', password=?';
            $params[] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }
        $sql .= ' WHERE id=?';
        $params[] = $id;
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $message = 'Utilisateur modifié !';
    } else {
        $message = 'Champs manquants.';
    }
}
if (isset($_POST['delete_user'])) {
    $id = intval($_POST['id']);
    $stmt = $pdo->prepare('DELETE FROM users WHERE id=?');
    $stmt->execute([$id]);
    $message = 'Utilisateur supprimé !';
}
$limit = 10;
if (isset($_GET['limit'])) {
    if ($_GET['limit'] === 'all') {
        $limit = null;
    } elseif (is_numeric($_GET['limit'])) {
        $limit = intval($_GET['limit']);
    }
}
if ($limit) {
    $users = $pdo->query('SELECT * FROM users LIMIT ' . $limit)->fetchAll();
} else {
    $users = $pdo->query('SELECT * FROM users')->fetchAll();
}
$showProducts = isset($_GET['showProducts']) && $_GET['showProducts'] == '1';
$productLimit = 10;
if (isset($_GET['product_limit'])) {
    if ($_GET['product_limit'] === 'all') {
        $productLimit = null;
    } elseif (is_numeric($_GET['product_limit'])) {
        $productLimit = intval($_GET['product_limit']);
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name = trim($_POST['name'] ?? '');
    $price = intval($_POST['price'] ?? 0);
    $category_id = intval($_POST['category_id'] ?? 0);
    $image = trim($_POST['image'] ?? '');
    $cover_image = trim($_POST['cover_image'] ?? '');
    if ($name && $price && $category_id) {
        $stmt = $pdo->prepare('INSERT INTO products (name, price, category_id, image, cover_image) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$name, $price, $category_id, $image, $cover_image]);
        $message = 'Produit ajouté !';
    } else {
        $message = 'Champs manquants pour le produit.';
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_product'])) {
    $id = intval($_POST['id']);
    $name = trim($_POST['name'] ?? '');
    $price = intval($_POST['price'] ?? 0);
    $category_id = intval($_POST['category_id'] ?? 0);
    $image = trim($_POST['image'] ?? '');
    $cover_image = trim($_POST['cover_image'] ?? '');
    if ($name && $price && $category_id) {
        $stmt = $pdo->prepare('UPDATE products SET name=?, price=?, category_id=?, image=?, cover_image=? WHERE id=?');
        $stmt->execute([$name, $price, $category_id, $image, $cover_image, $id]);
        $message = 'Produit modifié !';
    } else {
        $message = 'Champs manquants pour le produit.';
    }
}
if (isset($_POST['delete_product'])) {
    $id = intval($_POST['id']);
    $stmt = $pdo->prepare('DELETE FROM products WHERE id=?');
    $stmt->execute([$id]);
    $message = 'Produit supprimé !';
}
if ($productLimit) {
    $products = $pdo->query('SELECT * FROM products LIMIT ' . $productLimit)->fetchAll();
} else {
    $products = $pdo->query('SELECT * FROM products')->fetchAll();
}
$showCategories = isset($_GET['showCategories']) && $_GET['showCategories'] == '1';
$categoryLimit = 10;
if (isset($_GET['category_limit'])) {
    if ($_GET['category_limit'] === 'all') {
        $categoryLimit = null;
    } elseif (is_numeric($_GET['category_limit'])) {
        $categoryLimit = intval($_GET['category_limit']);
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $name = trim($_POST['name'] ?? '');
    if ($name) {
        $stmt = $pdo->prepare('INSERT INTO categories (name) VALUES (?)');
        $stmt->execute([$name]);
        $message = 'Catégorie ajoutée !';
    } else {
        $message = 'Nom de catégorie requis.';
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_category'])) {
    $id = intval($_POST['id']);
    $name = trim($_POST['name'] ?? '');
    if ($name) {
        $stmt = $pdo->prepare('UPDATE categories SET name=? WHERE id=?');
        $stmt->execute([$name, $id]);
        $message = 'Catégorie modifiée !';
    } else {
        $message = 'Nom de catégorie requis.';
    }
}
if (isset($_POST['delete_category'])) {
    $id = intval($_POST['id']);
    $stmt = $pdo->prepare('DELETE FROM categories WHERE id=?');
    $stmt->execute([$id]);
    $message = 'Catégorie supprimée !';
}
if ($categoryLimit) {
    $categoriesList = $pdo->query('SELECT * FROM categories LIMIT ' . $categoryLimit)->fetchAll();
} else {
    $categoriesList = $pdo->query('SELECT * FROM categories')->fetchAll();
}
$categories = $pdo->query('SELECT * FROM categories')->fetchAll();
