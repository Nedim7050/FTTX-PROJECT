# FTTX-PROJECT

Application web de suivi du marché FTTx pour le Centre Urbain Nord de Tunis (Tunisie Télécom).

## 📋 Description

Cette application PHP permet de suivre et gérer les projets FTTx avec un système de journalisation des opérations, un tableau de bord administratif et une gestion de clientèle.

## 🚀 Déploiement Cloud

Ce projet est configuré pour être déployé sur différentes plateformes cloud PHP. Voici les options recommandées :

### Option 1 : Render.com (⭐ Recommandé - Gratuit)
1. Créez un compte sur [Render.com](https://render.com)
2. Connectez votre dépôt GitHub
3. Créez un nouveau "Web Service" PHP
4. Configurez les variables d'environnement (voir ci-dessous)
5. Créez une base de données MySQL PostgreSQL sur Render
6. Déployez !

**Avantages :**
- Plan gratuit disponible
- Déploiement automatique depuis GitHub
- Base de données incluse

### Option 2 : Railway.app
1. Créez un compte sur [Railway.app](https://railway.app)
2. Connectez votre dépôt GitHub
3. Créez un nouveau projet
4. Ajoutez un service PHP et une base de données MySQL
5. Configurez les variables d'environnement
6. Déployez !

### Option 3 : Heroku
1. Installez [Heroku CLI](https://devcenter.heroku.com/articles/heroku-cli)
2. Créez un compte sur [Heroku](https://heroku.com)
3. Créez une nouvelle application
4. Ajoutez le buildpack PHP : `heroku buildpacks:set heroku/php`
5. Ajoutez une base de données ClearDB MySQL
6. Configurez les variables d'environnement
7. Déployez : `git push heroku main`

### Option 4 : DigitalOcean App Platform
1. Créez un compte sur [DigitalOcean](https://www.digitalocean.com)
2. Connectez votre dépôt GitHub
3. Créez une nouvelle App
4. Sélectionnez PHP comme runtime
5. Ajoutez une base de données MySQL
6. Configurez les variables d'environnement

## 🔧 Configuration

### Variables d'environnement requises

Configurez ces variables dans votre panneau d'hébergement cloud :

```
DB_HOST=votre-hote-db
DB_PORT=3306
DB_NAME=votre-nom-db
DB_USER=votre-utilisateur-db
DB_PASSWORD=votre-mot-de-passe-db
APP_ENV=production
```

### Configuration locale

1. Copiez le fichier `.env.example` en `.env`
2. Modifiez les valeurs selon votre environnement local
3. Assurez-vous que votre base de données MySQL est configurée

## 📁 Structure du projet

```
fttx_project/
├── admin_*.php          # Pages d'administration
├── dashboard.php        # Tableau de bord
├── database.php         # Configuration de la base de données
├── index.php            # Page d'accueil
├── journal.php          # Journal des opérations
├── login*.php           # Pages de connexion
├── css/                 # Fichiers CSS
├── js/                  # Fichiers JavaScript
├── uploads/             # Fichiers téléchargés
├── includes/            # Fichiers inclus
├── .htaccess           # Configuration Apache
├── .gitignore          # Fichiers ignorés par Git
└── README.md           # Ce fichier
```

## 🗄️ Base de données

Assurez-vous d'avoir une base de données MySQL créée avec le nom configuré dans vos variables d'environnement.

### Importation du schéma

Si vous avez un fichier SQL d'export :
```bash
mysql -u votre_utilisateur -p votre_db < schema.sql
```

## 📦 Prérequis

- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur / MariaDB
- Extension PDO MySQL pour PHP
- Apache avec mod_rewrite (optionnel)

## 🔒 Sécurité

- Les fichiers `.env` sont exclus de Git
- Les mots de passe ne doivent jamais être committés
- Configurez HTTPS en production
- Utilisez des mots de passe forts pour la base de données

## 📝 Déploiement étape par étape sur Render.com

### Étape 1 : Préparer le dépôt GitHub
```bash
git init
git add .
git commit -m "Initial commit"
git branch -M main
git remote add origin https://github.com/Nedim7050/FTTX-PROJECT.git
git push -u origin main
```

### Étape 2 : Créer un compte Render
1. Allez sur [render.com](https://render.com)
2. Inscrivez-vous avec votre compte GitHub

### Étape 3 : Créer une base de données
1. Dans le dashboard Render, cliquez sur "New +"
2. Sélectionnez "PostgreSQL" ou "MySQL"
3. Choisissez le plan gratuit
4. Notez les informations de connexion

### Étape 4 : Créer le Web Service
1. Cliquez sur "New +" > "Web Service"
2. Connectez votre dépôt GitHub
3. Sélectionnez le dépôt FTTX-PROJECT
4. Configuration :
   - **Name** : fttx-project
   - **Environment** : PHP
   - **Region** : Choix selon votre localisation
   - **Branch** : main
   - **Root Directory** : (laissez vide)
   - **Build Command** : (laissez vide)
   - **Start Command** : (laissez vide)

### Étape 5 : Configurer les variables d'environnement
Dans les paramètres du Web Service, ajoutez :
```
DB_HOST=votre-hote-de-render
DB_PORT=3306 (ou 5432 pour PostgreSQL)
DB_NAME=votre-nom-db
DB_USER=votre-user-db
DB_PASSWORD=votre-password-db
APP_ENV=production
```

### Étape 6 : Déployer
1. Cliquez sur "Create Web Service"
2. Render va automatiquement déployer votre application
3. Attendez quelques minutes pour le déploiement
4. Votre application sera accessible à l'URL fournie

## 🛠️ Maintenance

### Logs
Les logs de l'application sont disponibles dans le dashboard de votre hébergeur cloud.

### Mises à jour
1. Faites vos modifications
2. Committez et poussez vers GitHub
3. Le déploiement se fera automatiquement (si configuré)

## 📞 Support

Pour toute question ou problème, créez une issue sur GitHub.

## 📄 Licence

Ce projet est la propriété de Tunisie Télécom.

---

**Développé pour le Centre Urbain Nord de Tunis - Tunisie Télécom**

