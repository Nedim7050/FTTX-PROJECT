# Guide : Configuration de la Base de Données sur Render.com

## 🔴 Problème : "Erreur de connexion à la base de données"

Si vous voyez ce message, cela signifie que votre application PHP fonctionne, mais qu'elle ne peut pas se connecter à la base de données.

---

## ✅ Solutions Étape par Étape

### Étape 1 : Vérifier que la Base de Données est Créée

1. Dans le Dashboard Render, vérifiez si vous avez une base de données créée
2. Si vous n'avez pas de base de données :
   - Cliquez sur **"New +"**
   - Sélectionnez **"PostgreSQL"** (ou **"MySQL"** si disponible)
   - **Name** : `fttx-database`
   - **Database** : `fttx_project`
   - **Plan** : **Free**
   - Cliquez sur **"Create Database"**

### Étape 2 : Noter les Informations de Connexion

Une fois votre base de données créée, Render affiche les informations de connexion. **COPIEZ ces informations !**

Vous aurez besoin de :
- **Internal Database Host** (ou **Host**)
- **Port** (généralement `5432` pour PostgreSQL ou `3306` pour MySQL)
- **Database Name** (nom de la base de données)
- **Database User** (utilisateur)
- **Database Password** (mot de passe)

**⚠️ IMPORTANT :** Copiez ces valeurs quelque part (bloc-notes, etc.)

### Étape 3 : Configurer les Variables d'Environnement dans le Web Service

1. Dans le Dashboard Render, cliquez sur votre service **FTTX-PROJECT** (le Web Service)
2. Allez dans l'onglet **"Environment"** ou **"Settings"** > **"Environment"**
3. Trouvez la section **"Environment Variables"**

4. **Ajoutez chaque variable** en cliquant sur **"Add Environment Variable"** :

#### Pour PostgreSQL (si vous avez créé une base PostgreSQL) :

| Variable | Valeur | Exemple |
|----------|--------|---------|
| `DB_HOST` | **Internal Database Host** de votre DB | `dpg-xxxxx-a.oregon-postgres.render.com` |
| `DB_PORT` | `5432` | `5432` |
| `DB_NAME` | **Database Name** | `fttx_project` |
| `DB_USER` | **Database User** | `fttx_user` |
| `DB_PASSWORD` | **Database Password** | `[votre mot de passe]` |
| `APP_ENV` | `production` | `production` |

#### Pour MySQL (si vous avez créé une base MySQL) :

| Variable | Valeur | Exemple |
|----------|--------|---------|
| `DB_HOST` | **Internal Database Host** de votre DB | `dpg-xxxxx-a.oregon-mysql.render.com` |
| `DB_PORT` | `3306` | `3306` |
| `DB_NAME` | **Database Name** | `fttx_project` |
| `DB_USER` | **Database User** | `fttx_user` |
| `DB_PASSWORD` | **Database Password** | `[votre mot de passe]` |
| `APP_ENV` | `production` | `production` |

**⚠️ ATTENTION :** 
- Pour PostgreSQL, le port est **5432**
- Pour MySQL, le port est **3306**
- Utilisez **Internal Database Host** et non **External Database Host** si les deux sont affichés

### Étape 4 : Sauvegarder et Redéployer

1. Après avoir ajouté toutes les variables, cliquez sur **"Save Changes"**
2. Render va automatiquement redéployer votre application
3. Attendez 2-3 minutes

### Étape 5 : Tester la Connexion

1. Allez sur votre site : `https://fttx-project.onrender.com`
2. Vérifiez si l'erreur persiste
3. Si vous voyez toujours l'erreur, continuez avec l'étape 6

### Étape 6 : Utiliser le Script de Test

Un fichier `test-db-connection.php` a été créé pour vous aider à diagnostiquer.

1. Allez sur : `https://fttx-project.onrender.com/test-db-connection.php`
2. Ce script affichera :
   - Toutes les variables d'environnement détectées
   - Les valeurs utilisées pour la connexion
   - Le résultat de la tentative de connexion
   - Les erreurs détaillées si la connexion échoue

**📋 Utilisez les informations affichées pour identifier le problème :**
- Si les variables ne sont pas définies → Vérifiez l'étape 3
- Si les variables sont définies mais incorrectes → Vérifiez l'étape 2
- Si la connexion échoue avec les bonnes variables → Vérifiez que la base de données est accessible

---

## 🔍 Vérifications Importantes

### Vérification 1 : Base de Données dans la Même Région

Assurez-vous que votre base de données est dans la **même région** que votre Web Service :
- Si votre Web Service est dans **Oregon (US West)**
- Votre base de données doit aussi être dans **Oregon (US West)**

### Vérification 2 : Utiliser Internal Database Host

Render fournit deux types d'hôtes :
- **Internal Database Host** : Pour les services dans la même région (utilisez celui-ci)
- **External Database Host** : Pour les connexions externes (ne l'utilisez pas pour Render)

**Utilisez toujours Internal Database Host !**

### Vérification 3 : Type de Base de Données

Vérifiez que vous utilisez le bon port selon le type de base :
- **PostgreSQL** → Port `5432`
- **MySQL** → Port `3306`

### Vérification 4 : Format du Host

Le host doit ressembler à :
- PostgreSQL : `dpg-xxxxx-a.oregon-postgres.render.com`
- MySQL : `dpg-xxxxx-a.oregon-mysql.render.com`

---

## 🐛 Problèmes Courants et Solutions

### Problème 1 : Variables d'Environnement Non Définies

**Symptôme :** Le script de test montre "NON DÉFINI" pour toutes les variables

**Solution :**
1. Vérifiez que vous avez bien ajouté les variables dans Render
2. Vérifiez que vous avez cliqué sur "Save Changes"
3. Attendez 2-3 minutes après avoir sauvegardé pour que les variables soient prises en compte
4. Redéployez manuellement si nécessaire

### Problème 2 : Mauvaises Valeurs

**Symptôme :** Les variables sont définies mais avec de mauvaises valeurs

**Solution :**
1. Vérifiez les informations de connexion dans votre base de données Render
2. Copiez exactement les valeurs affichées
3. Assurez-vous qu'il n'y a pas d'espaces au début ou à la fin
4. Pour le mot de passe, copiez-le exactement tel quel

### Problème 3 : Base de Données Non Accessible

**Symptôme :** Erreur "Connection refused" ou "Host not found"

**Solution :**
1. Vérifiez que votre base de données est **active** (état "Available" dans Render)
2. Vérifiez que la base de données est dans la **même région** que votre Web Service
3. Utilisez **Internal Database Host** et non External
4. Vérifiez que le port est correct (5432 pour PostgreSQL, 3306 pour MySQL)

### Problème 4 : Type de Base de Données Incorrect

**Symptôme :** Erreur "Unknown database" ou "Access denied"

**Solution :**
1. Si vous avez créé une base **PostgreSQL** mais que votre code utilise MySQL :
   - Soit recréez une base MySQL
   - Soit modifiez `database.php` pour utiliser PostgreSQL (changez le DSN)
2. Vérifiez que le nom de la base de données est correct
3. Vérifiez que l'utilisateur et le mot de passe sont corrects

---

## 📋 Checklist Complète

- [ ] Base de données créée sur Render
- [ ] Informations de connexion copiées (Host, Port, Database, User, Password)
- [ ] 6 variables d'environnement ajoutées dans le Web Service :
  - [ ] `DB_HOST` (Internal Database Host)
  - [ ] `DB_PORT` (5432 pour PostgreSQL, 3306 pour MySQL)
  - [ ] `DB_NAME` (nom de la base)
  - [ ] `DB_USER` (utilisateur)
  - [ ] `DB_PASSWORD` (mot de passe)
  - [ ] `APP_ENV` (production)
- [ ] "Save Changes" cliqué
- [ ] Application redéployée
- [ ] Test avec `test-db-connection.php` effectué
- [ ] Base de données dans la même région que le Web Service
- [ ] Port correct selon le type de base (5432 ou 3306)

---

## 🔐 Sécurité

**⚠️ IMPORTANT :** 
- Ne partagez jamais vos mots de passe de base de données
- Supprimez le fichier `test-db-connection.php` après avoir résolu le problème
- Ne committez jamais les fichiers `.env` dans Git (ils sont déjà dans `.gitignore`)

---

## 📞 Si Rien Ne Fonctionne

1. Vérifiez les logs de votre application dans Render
2. Utilisez le script `test-db-connection.php` pour obtenir plus d'informations
3. Vérifiez la documentation Render : https://render.com/docs/databases
4. Contactez le support Render avec les détails de l'erreur

---

**Une fois la connexion établie, supprimez le fichier `test-db-connection.php` pour des raisons de sécurité ! 🗑️**

