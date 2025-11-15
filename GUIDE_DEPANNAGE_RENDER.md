# Guide de Dépannage - Erreurs Render.com

## 🐛 Erreur : "failed to read dockerfile: read /home/user/.local/tmp/buildkit-mount2676584650/src: is a directory"

### 🔍 Cause du Problème

Cette erreur signifie que Render ne peut pas trouver ou lire le Dockerfile. Cela peut être dû à :

1. **Le "Dockerfile Path" est incorrect dans Render**
2. **Le Dockerfile n'est pas à la racine du projet**
3. **Le nom du fichier est incorrect (doit être exactement "Dockerfile")**

---

## ✅ Solutions

### Solution 1 : Vérifier le "Dockerfile Path" dans Render

1. Allez dans le Dashboard Render
2. Cliquez sur votre service **FTTX-PROJECT**
3. Allez dans l'onglet **"Settings"** (Paramètres)
4. Vérifiez le champ **"Dockerfile Path"**
5. **Il doit être exactement :** `.` (point) ou **vide**
6. Si ce n'est pas le cas, modifiez-le et cliquez sur **"Save Changes"**
7. Relancez le déploiement (Render redéploiera automatiquement)

### Solution 2 : Vérifier que le Dockerfile est à la racine

Le Dockerfile doit être à la racine de votre projet, exactement comme ceci :

```
FTTX-PROJECT/
├── Dockerfile          ← ICI, à la racine
├── docker-entrypoint.sh
├── index.php
├── database.php
├── README.md
└── ... (autres fichiers)
```

### Solution 3 : Vérifier le nom du fichier

Le fichier doit s'appeler exactement **"Dockerfile"** (sans extension) :
- ✅ Correct : `Dockerfile`
- ❌ Incorrect : `Dockerfile.txt`
- ❌ Incorrect : `dockerfile` (minuscules)
- ❌ Incorrect : `Dockerfile.md`

### Solution 4 : Vérifier que le Dockerfile est dans Git

Assurez-vous que le Dockerfile est bien commité et poussé vers GitHub :

1. Vérifiez sur GitHub : https://github.com/Nedim7050/FTTX-PROJECT
2. Le fichier **Dockerfile** doit être visible à la racine du dépôt
3. Si ce n'est pas le cas, assurez-vous qu'il n'est pas dans `.gitignore`

---

## 🔧 Correction Rapide

### Étape 1 : Vérifier dans Render

1. Dans le Dashboard Render, cliquez sur **FTTX-PROJECT**
2. Allez dans **"Settings"** > **"Build & Deploy"**
3. Trouvez le champ **"Dockerfile Path"**
4. **Changez-le en :** `.` (point) ou **laissez vide**
5. Cliquez sur **"Save Changes"**

### Étape 2 : Vérifier sur GitHub

1. Allez sur : https://github.com/Nedim7050/FTTX-PROJECT
2. Vérifiez que le fichier **Dockerfile** est visible à la racine
3. Si ce n'est pas le cas, poussez-le vers GitHub :

```bash
git add Dockerfile
git commit -m "Fix: Add Dockerfile"
git push origin main
```

### Étape 3 : Redéployer

1. Dans Render, cliquez sur **"Manual Deploy"** > **"Deploy latest commit"**
2. Ou attendez que Render redéploie automatiquement (cela prend 5-10 minutes)

---

## 📋 Vérification Complète

### Checklist

- [ ] Le fichier `Dockerfile` existe à la racine du projet
- [ ] Le fichier s'appelle exactement **"Dockerfile"** (sans extension)
- [ ] Le "Dockerfile Path" dans Render est `.` (point) ou **vide**
- [ ] Le Dockerfile est visible sur GitHub à la racine du dépôt
- [ ] Le Dockerfile n'est pas dans `.gitignore`
- [ ] Le Dockerfile commence par `FROM php:8.1-apache`

---

## 🔄 Alternative : Supprimer et Recréer le Service

Si le problème persiste :

1. **Sauvegardez vos variables d'environnement** (copiez-les quelque part)
2. Dans Render, supprimez le service **FTTX-PROJECT**
3. Recréez un nouveau service :
   - Même configuration
   - **Dockerfile Path** : `.` (point) ou **vide**
   - Mêmes variables d'environnement
4. Déployez

---

## 📞 Si le Problème Persiste

1. Vérifiez les logs de build dans Render :
   - Dashboard > FTTX-PROJECT > "Logs" > "Build Logs"
2. Vérifiez que le Dockerfile est valide (syntaxe correcte)
3. Consultez la documentation Render : https://render.com/docs/docker
4. Créez une issue sur GitHub avec les détails de l'erreur

---

## ✅ Vérification du Dockerfile

Le Dockerfile doit commencer par :

```dockerfile
FROM php:8.1-apache
```

Et doit contenir toutes les étapes nécessaires (installer extensions, copier fichiers, etc.)

---

**Bonne chance avec le déploiement ! 🚀**

