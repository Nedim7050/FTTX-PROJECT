# FTTX-PROJECT

Application web professionnelle de suivi et gestion du marché FTTx développée pour le Centre Urbain Nord de Tunis - Tunisie Télécom.

## 📋 Description

FTTX-PROJECT est une plateforme web complète dédiée au suivi, à la gestion et au monitoring des opérations liées au marché FTTx (Fiber to the X). L'application offre une interface intuitive pour le suivi en temps réel des projets, la gestion administrative, la journalisation des opérations et le suivi des commissions.

## ✨ Fonctionnalités Principales

### 🎯 Tableau de Bord Interactif
- Vue d'ensemble en temps réel des projets FTTx
- Statistiques et indicateurs de performance
- Tableaux de bord personnalisés par rôle utilisateur

### 📊 Gestion Administrative
- **Gestion de la Clientèle** : Administration complète des clients et de leurs informations
- **Gestion SDR** : Suivi et administration des opérations SDR (Sales & Distribution)
- **Gestion SPI** : Administration des opérations SPI (Service Provider Interface)
- **Commission de Réception** : Suivi et gestion des commissions de réception

### 📝 Journal des Opérations
- Journalisation complète de toutes les opérations effectuées
- Historique détaillé avec horodatage
- Système de logs pour traçabilité complète
- Suppression sécurisée des logs (avec authentification)

### 🔐 Système d'Authentification
- Connexion sécurisée avec différents niveaux d'accès
- Authentification pour le journal administratif
- Gestion des sessions utilisateur
- Déconnexion sécurisée

### 📄 Gestion des Documents
- Visualisation de documents PDF
- Upload et gestion de fichiers
- Organisation des documents par projet

## 🏗️ Architecture Technique

### Stack Technologique
- **Backend** : PHP 7.4+ avec architecture MVC
- **Base de Données** : MySQL/MariaDB avec support PostgreSQL
- **Frontend** : HTML5, CSS3, JavaScript (ES6+)
- **Framework CSS** : Bootstrap 4.5.2
- **Icons** : Font Awesome 5.15.4
- **Connexion DB** : PDO (PHP Data Objects) pour la sécurité et la performance

### Structure du Projet

```
fttx_project/
├── index.php                    # Page d'accueil principale
├── dashboard.php                # Tableau de bord interactif
├── database.php                 # Configuration de la base de données
├── login.php                    # Page de connexion principale
├── login_journal.php            # Connexion pour le journal admin
├── logout.php                   # Déconnexion sécurisée
│
├── admin_clientele.php          # Administration de la clientèle
├── admin_sdr.php                # Administration SDR
├── admin_spi.php                # Administration SPI
├── admin_commission_reception.php # Gestion des commissions
│
├── journal.php                  # Journal des opérations
├── initialize_journal_admin.php # Initialisation admin journal
├── delete_log.php               # Suppression sécurisée des logs
│
├── view_pdf.php                 # Visualisation de documents PDF
├── serve_file.php               # Service de fichiers
├── setup_users.php              # Configuration des utilisateurs
├── supprimer_operation.php      # Suppression d'opérations
│
├── css/
│   └── style.css                # Styles personnalisés
├── js/                          # Scripts JavaScript
├── includes/                    # Fichiers inclus (headers, footers, etc.)
└── uploads/                     # Fichiers téléchargés (images, documents)
```

## 🚀 Installation

### Prérequis
- PHP 7.4 ou supérieur
- MySQL 5.7+ / MariaDB 10.3+ ou PostgreSQL 12+
- Apache avec mod_rewrite activé (optionnel)
- Extensions PHP : PDO, PDO_MySQL (ou PDO_PGSQL pour PostgreSQL), GD, ZIP

### Installation Locale

1. **Cloner le dépôt**
   ```bash
   git clone https://github.com/Nedim7050/FTTX-PROJECT.git
   cd FTTX-PROJECT
   ```

2. **Configurer la base de données**
   - Créez une base de données MySQL/PostgreSQL
   - Importez le schéma de base de données si disponible
   - Configurez les informations de connexion dans `database.php` ou via variables d'environnement

3. **Configurer les variables d'environnement**
   - Créez un fichier `.env` à la racine (basé sur `.env.example` si disponible)
   - Configurez les variables suivantes :
     ```
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_NAME=fttx_project
     DB_USER=root
     DB_PASSWORD=votre_mot_de_passe
     APP_ENV=development
     ```

4. **Configurer les permissions**
   - Assurez-vous que le dossier `uploads/` est accessible en écriture
   ```bash
   chmod -R 755 uploads/
   ```

5. **Initialiser les utilisateurs**
   - Exécutez `setup_users.php` pour configurer les comptes utilisateurs initiaux

## ⚙️ Configuration

### Base de Données

L'application supporte automatiquement MySQL et PostgreSQL. La détection se fait automatiquement selon le port configuré :
- Port `3306` → MySQL
- Port `5432` → PostgreSQL

### Variables d'Environnement

L'application utilise des variables d'environnement pour la configuration, permettant une flexibilité entre développement et production :

| Variable | Description | Défaut |
|----------|-------------|--------|
| `DB_HOST` | Adresse du serveur de base de données | `127.0.0.1` |
| `DB_PORT` | Port de la base de données | `3306` (MySQL) ou `5432` (PostgreSQL) |
| `DB_NAME` | Nom de la base de données | `fttx_project` |
| `DB_USER` | Utilisateur de la base de données | `root` |
| `DB_PASSWORD` | Mot de passe de la base de données | (vide) |
| `APP_ENV` | Environnement (development/production) | `development` |

## 🔒 Sécurité

- **Connexions sécurisées** : Utilisation de PDO avec requêtes préparées pour prévenir les injections SQL
- **Authentification** : Système de connexion avec gestion de sessions sécurisée
- **Protection des fichiers sensibles** : Exclusion des fichiers `.env` via `.gitignore`
- **Validation des entrées** : Validation et sanitization des données utilisateur
- **Gestion des erreurs** : Messages d'erreur adaptés selon l'environnement (développement vs production)

## 📱 Interface Utilisateur

L'application propose une interface moderne et responsive :
- Design responsive s'adaptant à tous les écrans (desktop, tablette, mobile)
- Navigation intuitive avec menu de navigation clair
- Animations CSS pour une meilleure expérience utilisateur
- Interface cohérente avec la charte graphique Tunisie Télécom

## 🛠️ Développement

### Structure MVC
L'application suit une architecture MVC (Model-View-Controller) :
- **Model** : `database.php` - Gestion des données
- **View** : Fichiers PHP avec HTML/PHP mélangé
- **Controller** : Logique métier dans chaque fichier PHP

### Contribution
Pour contribuer au projet :
1. Forkez le dépôt
2. Créez une branche pour votre fonctionnalité (`git checkout -b feature/ma-fonctionnalite`)
3. Committez vos changements (`git commit -m 'Ajout de ma fonctionnalité'`)
4. Pushez vers la branche (`git push origin feature/ma-fonctionnalite`)
5. Ouvrez une Pull Request

## 📞 Support et Contact

Pour toute question, suggestion ou problème :
- Créez une issue sur [GitHub](https://github.com/Nedim7050/FTTX-PROJECT/issues)
- Contactez l'équipe de développement du Centre Urbain Nord

## 👨‍💻 Auteur

**Nedim Mejri**

Développeur de l'application FTTX-PROJECT pour le Centre Urbain Nord de Tunis - Tunisie Télécom.

## 📄 Licence

Ce projet est la propriété exclusive de **Tunisie Télécom** et est destiné à un usage interne.

---

**Développé avec ❤️ par Nedim Mejri pour le Centre Urbain Nord de Tunis - Tunisie Télécom**

© 2024 Tunisie Télécom. Tous droits réservés.
