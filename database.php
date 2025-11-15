<?php
// Connexion à la base de données avec PDO
// Utilisation des variables d'environnement pour la compatibilité cloud
// En local, ces variables peuvent être définies dans un fichier .env
// En production cloud, elles seront définies dans le panneau de configuration

// Charger les variables d'environnement si un fichier .env existe (pour développement local)
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue; // Ignorer les commentaires
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

// Récupération des variables d'environnement avec valeurs par défaut pour le développement local
$host = $_ENV['DB_HOST'] ?? $_SERVER['DB_HOST'] ?? getenv('DB_HOST') ?: '127.0.0.1';
$dbname = $_ENV['DB_NAME'] ?? $_SERVER['DB_NAME'] ?? getenv('DB_NAME') ?: 'fttx_project';
$username = $_ENV['DB_USER'] ?? $_SERVER['DB_USER'] ?? getenv('DB_USER') ?: 'root';
$password = $_ENV['DB_PASSWORD'] ?? $_SERVER['DB_PASSWORD'] ?? getenv('DB_PASSWORD') ?: '';

// Port de la base de données (optionnel, pour certains hébergeurs cloud)
$port = $_ENV['DB_PORT'] ?? $_SERVER['DB_PORT'] ?? getenv('DB_PORT') ?: '3306';

try {
    // Détecter le type de base de données à partir du port ou d'une variable d'environnement
    $db_type = $_ENV['DB_TYPE'] ?? $_SERVER['DB_TYPE'] ?? getenv('DB_TYPE') ?: 'mysql';
    
    // Si le port est 5432, c'est PostgreSQL, sinon MySQL par défaut
    if ($port == '5432' || $port == 5432) {
        $db_type = 'pgsql';
    }
    
    // Construire le DSN selon le type de base de données
    if ($db_type === 'pgsql' || $db_type === 'postgresql') {
        // PostgreSQL
        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    } else {
        // MySQL (par défaut)
        $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8";
    }
    
    $conn = new PDO($dsn, $username, $password);
    
    // Configuration de PDO pour lever une exception en cas d'erreur
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Message de succès pour confirmation (à supprimer ou commenter en production)
    // echo 'Connexion réussie à la base de données.';

} catch (PDOException $e) {
    // Message d'erreur en cas de problème de connexion
    // En production, ne pas afficher les détails de l'erreur
    $errorMessage = 'Erreur de connexion à la base de données.';
    if (isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] === 'development') {
        $errorMessage .= ' Détails : ' . $e->getMessage();
    }
    die($errorMessage);
}
?>
