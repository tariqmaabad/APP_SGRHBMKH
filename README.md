# Système de Gestion des Ressources Humaines (SGRH-BMKH)

## Description du Projet

Le SGRH-BMKH est une solution complète de gestion des ressources humaines spécialement conçue pour le secteur de la santé. Cette application web moderne utilise une architecture MVC en PHP pour offrir une plateforme robuste et évolutive de gestion du personnel médical et administratif.

### Objectifs Principaux

- Centraliser et digitaliser la gestion des ressources humaines
- Optimiser le suivi du personnel médical et administratif
- Faciliter la prise de décision grâce à des tableaux de bord analytiques
- Améliorer l'efficacité des processus RH
- Assurer une meilleure répartition des ressources humaines

### Public Cible

- Gestionnaires RH du secteur santé
- Directeurs d'établissements de santé
- Administrateurs du système de santé
- Responsables des formations sanitaires
- Personnel des ressources humaines

## Caractéristiques Techniques

- **Architecture** : MVC (Modèle-Vue-Contrôleur)
- **Backend** : PHP 7.4+
- **Base de données** : MySQL 5.7+
- **Frontend** : HTML5, CSS3, JavaScript
- **Bibliothèques** : 
  - JSCharting pour les visualisations de données
  - Bootstrap pour l'interface responsive
  - Font Awesome pour les icônes
  - jQuery pour les interactions dynamiques

## Fonctionnalités Détaillées

### 1. Gestion du Personnel
- Dossiers complets des employés
  - Informations personnelles et professionnelles
  - Historique des affectations
  - Qualifications et certifications
  - Documents administratifs
- Gestion des contrats et statuts
- Suivi des congés et absences
- Évaluation des performances

### 2. Gestion des Établissements
- Cartographie des formations sanitaires
- Classification par type (urbain/rural)
- Capacité et spécialités
- Besoins en personnel
- Taux d'occupation des postes

### 3. Mouvements du Personnel
- Suivi en temps réel des affectations
- Gestion des mutations et détachements
- Historique des mouvements
- Planification des rotations
- Rapports de mobilité

### 4. Tableau de Bord Analytique
- Statistiques détaillées du personnel
- Répartition par genre et situation familiale
- Analyse des tendances RH
- Indicateurs de performance
- Rapports personnalisables
- Visualisations interactives avec JSCharting

### 5. Administration et Sécurité
- Gestion des utilisateurs et rôles
- Journalisation des actions
- Protection des données sensibles
- Sauvegarde automatique
- Authentification sécurisée

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

## Support et Contact

Pour toute question ou assistance :
- Documentation : Consulter le wiki du projet
- Bugs : Ouvrir une issue sur GitHub
- Support technique : Contacter l'équipe de maintenance
- Questions générales : Contacter l'administrateur système

Pour plus d'informations, visitez notre page de documentation ou rejoignez notre communauté de développeurs.
