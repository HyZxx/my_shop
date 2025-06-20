# My Shop - SkinVault

## Description

My Shop est un site e-commerce spécialisé dans la vente d'armes CSGO (Counter-Strike: Global Offensive). Le projet propose une plateforme complète avec deux interfaces principales :

- **Interface utilisateur** : Page d'accueil permettant de consulter et rechercher les produits disponibles
- **Interface administrateur** : Dashboard complet pour la gestion des utilisateurs, produits et catégories

## Fonctionnalités

### Pour les utilisateurs
- Consultation des produits disponibles
- Recherche en temps réel par nom ou catégorie
- Affichage détaillé des produits via popup
- Interface responsive et moderne

### Pour les administrateurs
- Gestion complète des utilisateurs (CRUD)
- Gestion des produits (CRUD)
- Gestion des catégories (CRUD)
- Dashboard intuitif avec navigation par sections

## Technologies utilisées

- **Backend** : PHP
- **Base de données** : MySQL
- **Frontend** : HTML, CSS
- **Design** : Interface responsive avec thème sombre

## Installation

1. Clonez le repository
2. Configurez votre base de données MySQL
3. Importez le schéma de base de données depuis le dossier `db/`
4. Configurez les paramètres de connexion dans `db/db_connect.php`

## Lancer le projet

Pour démarrer le serveur de développement PHP, exécutez la commande suivante :

```bash
php -S localhost:5500
```

Le site sera alors accessible en local via l'URL indiquée dans le terminal.

## Utilisation

1. Accédez à `http://localhost:5500`
2. Connectez-vous avec vos identifiants
3. **Utilisateur standard** : Accès à la page d'accueil avec les produits
4. **Administrateur** : Accès au dashboard pour la gestion complète

## Auteur

Développé dans le cadre d'un projet de formation en tant que développeur web.