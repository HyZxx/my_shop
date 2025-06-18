<?php
function getPDO() {
    $host = '127.0.0.1';
    $db   = 'my_shop';
    $user = 'lucas'; 
    $pass = 'password';
    $charset = 'utf8';
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    try {
        return new PDO($dsn, $user, $pass, $options);
    } catch (PDOException $e) {
        die('Erreur connexion DB: ' . $e->getMessage());
    }
}
