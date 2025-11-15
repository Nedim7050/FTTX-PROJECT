# Guide de Déploiement Cloud - FTTX PROJECT

Ce guide vous accompagne étape par étape pour déployer votre application PHP sur un serveur cloud.

## 🎯 Choix de la Plateforme

Pour une application PHP, voici les meilleures options :

### 1. Render.com (⭐ RECOMMANDÉ - Gratuit)
- **Avantages** : Plan gratuit, déploiement automatique, base de données incluse
- **Prix** : Gratuit avec limitations, payant à partir de 7$/mois
- **Lien** : https://render.com

### 2. Railway.app
- **Avantages** : Interface moderne, facile à utiliser
- **Prix** : 5$ de crédit gratuit/mois
- **Lien** : https://railway.app

### 3. Heroku
- **Avantages** : Très populaire, nombreux add-ons
- **Prix** : Payant uniquement (7$/mois minimum)
- **Lien** : https://heroku.com

---

## 📦 ÉTAPE 1 : Préparer le Projet Local

### Vérifier que tous les fichiers sont prêts

Assurez-vous d'avoir :
- ✅ `.gitignore` créé
- ✅ `database.php` modifié pour utiliser les variables d'environnement
- ✅ `README.md` créé
- ✅ `.htaccess` créé

---

## 🔄 ÉTAPE 2 : Initialiser Git et Pousser vers GitHub

### 2.1. Vérifier l'état de Git

Ouvrez votre terminal dans le dossier du projet et exécutez :

```bash
git status
```

### 2.2. Initialiser Git (si pas déjà fait)

```bash
git init
```

### 2.3. Créer le fichier README.md (déjà fait)

```bash
# Le fichier README.md existe déjà, on peut passer à l'étape suivante
```

### 2.4. Ajouter tous les fichiers

```bash
git add .
```

### 2.5. Faire le premier commit

```bash
git commit -m "Initial commit - Project ready for cloud deployment"
```

### 2.6. Renommer la branche en main (si nécessaire)

```bash
git branch -M main
```

### 2.7. Ajouter le dépôt distant GitHub

```bash
git remote add origin https://github.com/Nedim7050/FTTX-PROJECT.git
```

**⚠️ IMPORTANT** : Si le dépôt distant existe déjà, vous devrez peut-être d'abord le supprimer :
```bash
git remote remove origin
git remote add origin https://github.com/Nedim7050/FTTX-PROJECT.git
```

### 2.8. Pousser vers GitHub

```bash
git push -u origin main
```

**Note** : Si vous avez des erreurs d'authentification, vous devrez peut-être configurer un token GitHub :
1. Allez sur GitHub > Settings > Developer settings > Personal access tokens
2. Créez un token avec les permissions `repo`
3. Utilisez le token comme mot de passe lors du push

---

## ☁️ ÉTAPE 3 : Déployer sur Render.com

### 3.1. Créer un compte Render

1. Allez sur [https://render.com](https://render.com)
2. Cliquez sur "Get Started for Free"
3. Inscrivez-vous avec votre compte GitHub (recommandé)

### 3.2. Créer une base de données MySQL

1. Dans le Dashboard Render, cliquez sur **"New +"**
2. Sélectionnez **"PostgreSQL"** ou **"MySQL"**
   - Pour MySQL, Render utilise généralement PostgreSQL, mais vous pouvez trouver des alternatives
   - **Alternative** : Utilisez une base de données externe comme [PlanetScale](https://planetscale.com) ou [Aiven](https://aiven.io)
3. Choisissez le plan **"Free"**
4. Nommez votre base : `fttx-database`
5. Cliquez sur **"Create Database"**
6. **Important** : Notez les informations de connexion (Host, Database, User, Password)

### 3.3. Créer le Web Service

1. Dans le Dashboard, cliquez sur **"New +"**
2. Sélectionnez **"Web Service"**
3. Connectez votre dépôt GitHub
4. Sélectionnez le dépôt **"FTTX-PROJECT"**
5. Configurez le service :
   - **Name** : `fttx-project`
   - **Environment** : `PHP`
   - **Region** : Choisissez la région la plus proche de vous
   - **Branch** : `main`
   - **Root Directory** : (laissez vide)
   - **Build Command** : (laissez vide)
   - **Start Command** : `php -S 0.0.0.0:$PORT` (Render utilisera automatiquement le port via $PORT)
6. Cliquez sur **"Advanced"** pour configurer les variables d'environnement

### 3.4. Configurer les Variables d'Environnement

Dans la section **"Environment Variables"**, ajoutez :

```
DB_HOST=votre-hote-db.render.com
DB_PORT=3306
DB_NAME=votre-nom-db
DB_USER=votre-utilisateur-db
DB_PASSWORD=votre-mot-de-passe-db
APP_ENV=production
```

**Où trouver ces valeurs ?**
- Retournez à votre base de données créée à l'étape 3.2
- Copiez les informations de connexion depuis l'onglet "Connections"

### 3.5. Déployer

1. Cliquez sur **"Create Web Service"**
2. Render va automatiquement :
   - Cloner votre dépôt GitHub
   - Installer les dépendances (si composer.json existe)
   - Démarrer votre application
3. Attendez 2-5 minutes pour le premier déploiement
4. Une fois terminé, votre application sera disponible à l'URL fournie (ex: `https://fttx-project.onrender.com`)

### 3.6. Vérifier le Déploiement

1. Visitez l'URL de votre application
2. Vérifiez que la page d'accueil s'affiche
3. Testez la connexion à la base de données

---

## 🚂 ÉTAPE 4 : Alternative - Déployer sur Railway.app

### 4.1. Créer un compte Railway

1. Allez sur [https://railway.app](https://railway.app)
2. Inscrivez-vous avec GitHub

### 4.2. Créer un nouveau projet

1. Cliquez sur **"New Project"**
2. Sélectionnez **"Deploy from GitHub repo"**
3. Choisissez le dépôt **"FTTX-PROJECT"**

### 4.3. Ajouter une base de données MySQL

1. Dans votre projet, cliquez sur **"+ New"**
2. Sélectionnez **"Database"** > **"MySQL"**
3. Railway créera automatiquement une base de données

### 4.4. Configurer les variables d'environnement

1. Cliquez sur votre service PHP
2. Allez dans l'onglet **"Variables"**
3. Ajoutez les variables :
   ```
   DB_HOST=${{MySQL.MYSQLHOST}}
   DB_PORT=${{MySQL.MYSQLPORT}}
   DB_NAME=${{MySQL.MYSQLDATABASE}}
   DB_USER=${{MySQL.MYSQLUSER}}
   DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}
   APP_ENV=production
   ```
   Railway fournit automatiquement ces références pour la base de données liée.

### 4.5. Déployer

Railway déploiera automatiquement votre application. L'URL sera générée automatiquement.

---

## 🔧 ÉTAPE 5 : Importation de la Base de Données

### 5.1. Préparer votre fichier SQL

Si vous avez un export SQL de votre base de données locale :

1. Exportez votre base de données locale :
   ```bash
   mysqldump -u root -p fttx_project > database_export.sql
   ```

2. Ou utilisez phpMyAdmin pour exporter

### 5.2. Importer sur Render/Railway

#### Option A : Via ligne de commande (recommandé)

```bash
# Pour Render PostgreSQL (si vous utilisez PostgreSQL)
psql "postgresql://user:password@host:5432/dbname" < database_export.sql

# Pour MySQL
mysql -h host -u user -p database_name < database_export.sql
```

#### Option B : Via l'interface web

1. Connectez-vous à votre base de données via un client MySQL (MySQL Workbench, DBeaver, etc.)
2. Utilisez les informations de connexion de votre base cloud
3. Importez votre fichier SQL

---

## ✅ ÉTAPE 6 : Vérifications Post-Déploiement

### Checklist :

- [ ] L'application est accessible via l'URL fournie
- [ ] La page d'accueil s'affiche correctement
- [ ] Les images et assets se chargent
- [ ] La connexion à la base de données fonctionne
- [ ] Le système de login fonctionne
- [ ] Les fichiers uploads sont accessibles
- [ ] Les logs sont consultables dans le dashboard

---

## 🐛 Dépannage

### Problème : Erreur de connexion à la base de données

**Solution** :
1. Vérifiez que toutes les variables d'environnement sont correctement définies
2. Vérifiez que le host de la base de données est accessible depuis l'application
3. Certains hébergeurs nécessitent un whitelisting d'IP (peu probable avec Render/Railway)

### Problème : Les images ne s'affichent pas

**Solution** :
1. Vérifiez que le dossier `uploads/` est bien inclus dans Git
2. Vérifiez les permissions des fichiers
3. Vérifiez les chemins dans votre code (utilisez des chemins relatifs)

### Problème : Erreur 500

**Solution** :
1. Consultez les logs dans le dashboard de votre hébergeur
2. Vérifiez que PHP 7.4+ est utilisé
3. Vérifiez la syntaxe PHP (pas d'erreurs)

### Problème : Application ne démarre pas

**Solution** :
1. Vérifiez que le fichier `index.php` existe à la racine
2. Vérifiez que la commande de démarrage est correcte
3. Pour Render, utilisez : `php -S 0.0.0.0:$PORT`

---

## 📚 Ressources Utiles

- [Documentation Render](https://render.com/docs)
- [Documentation Railway](https://docs.railway.app)
- [Documentation Heroku PHP](https://devcenter.heroku.com/articles/getting-started-with-php)

---

## 🆘 Support

Si vous rencontrez des problèmes :
1. Consultez les logs de votre application dans le dashboard
2. Vérifiez la documentation de votre hébergeur
3. Créez une issue sur GitHub avec les détails de l'erreur

---

**Bon déploiement ! 🚀**

