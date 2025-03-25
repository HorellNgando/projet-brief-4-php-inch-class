# Gestion des Utilisateurs - Application Web PHP

## Description

Ce projet est une application web de gestion des utilisateurs développée en PHP avec MySQL comme base de données. L'application permet :

- L'authentification sécurisée des utilisateurs (connexion par email)
- La gestion des profils utilisateurs
- Un système de rôles (Administrateur et Client)
- Le suivi des sessions de connexion/déconnexion
- L'administration complète des utilisateurs pour les administrateurs

**Note importante** : Ce projet a été développé sans utiliser l'architecture MVC (Modèle-Vue-Contrôleur). Tous les traitements et l'affichage sont regroupés dans des fichiers uniques pour chaque fonctionnalité.

## Fonctionnalités

### Pour les administrateurs
- Tableau de bord avec statistiques
- Gestion complète des utilisateurs (création, modification, suppression)
- Activation/désactivation des comptes
- Attribution des rôles
- Consultation des logs de connexion/déconnexion
- Ajout rapide d'utilisateurs depuis le dashboard

### Pour les clients
- Inscription et connexion sécurisée
- Gestion du profil personnel
- Consultation de l'historique de ses connexions
- Modification des informations personnelles

## Technologies utilisées

- **Backend** : PHP (procédural)
- **Base de données** : MySQL
- **Frontend** : HTML5, CSS3, Bootstrap 5
- **Sécurité** :
  - Hashage des mots de passe avec bcrypt
  - Protection contre les injections SQL
  - Gestion des sessions sécurisée
  - Protection XSS

## Prérequis

- Serveur web (Apache, Nginx)
- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur
- Composer (pour l'autoloading si nécessaire)

## Installation

1. Cloner le dépôt :
```bash
git clone https://github.com/HorellNgando/projet-brief-4-php-inch-class
```

2. Configurer la base de données :
- Importer le fichier SQL `database.sql` situé dans le dossier `sql/`
- Modifier les informations de connexion dans `config.php`

3. Configurer l'application :
- Modifier les constantes dans `config.php` selon votre environnement

4. Accéder à l'application :
- Ouvrir un navigateur et accéder à `http://localhost/gestion-utilisateurs`

## Structure du projet

```
/gestion-utilisateurs
│
├── config.php          # Configuration de la base de données
├── auth.php            # Fonctions d'authentification
├── index.php           # Page de connexion
├── register.php        # Page d'inscription
├── dashboard.php       # Tableau de bord
├── profile.php         # Gestion du profil
├── users.php           # Gestion des utilisateurs (admin)
├── logs.php            # Consultation des logs
├── logout.php          # Déconnexion
├── sql/
│   └── database.sql    # Script SQL de la base de données
├── assets/
│   ├── css/
│   │   └── style.css   # CSS personnalisé
│   └── js/
│       └── script.js   # JavaScript personnalisé
└── includes/           # Fonctions utilitaires
    ├── header.php      # En-tête commun
    ├── footer.php      # Pied de page commun
    └── sidebar.php     # Barre latérale
```

## Comptes par défaut

Un administrateur est créé par défaut :
- **Email** : admin@example.com
- **Mot de passe** : admin123

## Sécurité

Bien que ce projet inclue des mesures de sécurité de base, il est important de noter que :

1. Le projet n'utilise pas d'architecture MVC, ce qui peut rendre le code moins maintenable pour des projets plus importants
2. Pour une utilisation en production, des mesures de sécurité supplémentaires seraient nécessaires :
   - Protection CSRF
   - Limitation des tentatives de connexion
   - Journalisation plus avancée
   - Configuration sécurisée du serveur

## Contribution

Ce projet n'est pas maintenu activement car il s'agit d'une démonstration pédagogique. Cependant, les contributions sont les bienvenues sous forme de forks et pull requests.

**Note sur l'architecture** : Ce projet a été délibérément développé sans utiliser l'architecture MVC afin de se concentrer sur les fonctionnalités de base. Car je n'ai pas toujours reussi à prendre en main l'architecture MVC qui est plutôt complexe. J'espère d'ici là avoir les aptitudes nécessaire pour prendre en main cette architecture avant la fin de la formation.