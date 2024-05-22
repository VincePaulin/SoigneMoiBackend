# SoigneMoi

SoigneMoi est une application web destinée à améliorer l'efficacité de l'accueil des patients et la gestion des plannings des praticiens dans l'hôpital SoigneMoi de la région lilloise. Ceci est l'api backend des applications.

## Table des matières

- [Prérequis](#prérequis)
- [Installation](#installation)
- [Configuration](#configuration)
- [Utilisation](#utilisation)
- [Déploiement](#déploiement)

## Prérequis

Avant de commencer, assurez-vous d'avoir les éléments suivants installés sur votre machine :
- PHP 8.x
- Composer
- MySQL
- Node.js (pour la gestion des dépendances front-end)
- Git

## Installation

1. Clonez le dépôt GitHub :

    ```bash
    git clone https://github.com/VincePaulin/SoigneMoiBackend.git
    cd soignemoi
    ```

2. Installez les dépendances PHP avec Composer :

    ```bash
    composer install
    ```

## Configuration

1. Copiez le fichier `.env.example` en `.env` :

    ```bash
    cp .env.example .env
    ```

2. Configurez les variables d'environnement dans le fichier `.env`, notamment la connexion à la base de données :

    ```dotenv
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=soignemoi
    DB_USERNAME=votre-utilisateur
    DB_PASSWORD=votre-mot-de-passe
    ```

3. Générez la clé de l'application Laravel :

    ```bash
    php artisan key:generate
    ```

4. Exécutez les migrations et seeders pour créer et remplir la base de données :

    ```bash
    php artisan migrate
    ```

5. Intégrez les données démos :

    ```bash
    php artisan db:seed --class=databaseSeederDemo
    ```

## Utilisation

1. Démarrez le serveur de développement Laravel :

    ```bash
    php artisan serve
    ```

## Déploiement

Pour déployer l'application, suivez les étapes ci-dessous :

1. Configurez l'environnement de production dans le fichier `.env`.
2. Utilisez un service de déploiement comme Fly.io :

    ```bash
    flyctl deploy
    ```