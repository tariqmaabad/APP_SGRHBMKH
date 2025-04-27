# Système de Gestion des Ressources Humaines (SGRH-BMKH)

Application de gestion des ressources humaines avec architecture MVC en PHP.

## Fonctionnalités

- Gestion des données du personnel (informations personnelles, postes)
- Suivi des mouvements du personnel en temps réel
- Gestion des workflows RH (intégration, départ, mobilité)
- Tableau de bord interactif avec statistiques
- Gestion des formations sanitaires et provinces
- Système d'authentification et de contrôle d'accès

## Prérequis

- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur
- Apache 2.4 ou supérieur
- Composer (optionnel)

## Installation

1. Cloner le projet dans le répertoire web de votre serveur :
```bash
git clone https://github.com/votre-username/APP_SGRHBMKH.git
cd APP_SGRHBMKH
```

2. Créer une base de données MySQL et importer le schéma :
```bash
mysql -u root -p
CREATE DATABASE sgrhbmkh_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
exit;
mysql -u root -p sgrhbmkh_db < config/schema.sql
```

3. Configurer les accès à la base de données dans `config/database.php` :
```php
private $host = "localhost";
private $db_name = "sgrhbmkh_db";
private $username = "votre_username";
private $password = "votre_password";
```

4. Configurer le serveur web pour pointer vers le répertoire du projet.

5. Configurer les droits d'accès sur les dossiers :
```bash
chmod -R 755 .
chmod -R 777 assets/uploads (si utilisé)
```

## Structure du Projet

```
APP_SGRHBMKH/
├── config/             # Configuration de la base de données et schéma
├── controllers/        # Contrôleurs de l'application
├── models/            # Modèles de données
├── views/             # Vues de l'application
│   ├── auth/          # Pages d'authentification
│   ├── dashboard/     # Tableau de bord
│   ├── errors/        # Pages d'erreur
│   ├── layout/        # Template principal
│   └── personnel/     # Gestion du personnel
├── assets/            # Ressources statiques
│   ├── css/          # Feuilles de style
│   ├── js/           # Scripts JavaScript
│   └── img/          # Images
└── utils/             # Utilitaires et helpers

```

## Architecture MVC

L'application suit une architecture MVC (Modèle-Vue-Contrôleur) :

- **Modèles** : Gestion des données et logique métier
- **Vues** : Interface utilisateur et présentation
- **Contrôleurs** : Traitement des requêtes et coordination

## Utilisation

1. Accéder à l'application via votre navigateur :
```
http://localhost/APP_SGRHBMKH
```

2. Connexion avec les identifiants par défaut :
```
Email : admin@example.com
Mot de passe : password
```

## Sécurité

- Protection CSRF sur tous les formulaires
- Sessions sécurisées
- Validation des entrées utilisateur
- Échappement des sorties HTML
- Requêtes préparées pour la base de données

## Maintenance

- Vérifier régulièrement les logs d'erreur
- Sauvegarder la base de données régulièrement
- Mettre à jour les dépendances si nécessaire
- Surveiller l'espace disque pour les fichiers uploadés

## Contribution

1. Fork le projet
2. Créer une branche pour votre fonctionnalité
3. Commit vos changements
4. Push vers la branche
5. Créer une Pull Request

## Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

## Support

Pour toute question ou problème :
- Ouvrir une issue sur GitHub
- Contacter l'administrateur système



## t
