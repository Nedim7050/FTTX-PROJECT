<?php
// Script de test pour vérifier la connexion à la base de données
// Ce fichier vous aidera à diagnostiquer les problèmes de connexion

echo "<h1>Test de Connexion à la Base de Données</h1>";
echo "<hr>";

// Afficher toutes les variables d'environnement (pour debug)
echo "<h2>Variables d'Environnement Détectées :</h2>";
echo "<pre>";
echo "DB_HOST: " . (isset($_ENV['DB_HOST']) ? $_ENV['DB_HOST'] : 'NON DÉFINI') . "\n";
echo "DB_PORT: " . (isset($_ENV['DB_PORT']) ? $_ENV['DB_PORT'] : 'NON DÉFINI') . "\n";
echo "DB_NAME: " . (isset($_ENV['DB_NAME']) ? $_ENV['DB_NAME'] : 'NON DÉFINI') . "\n";
echo "DB_USER: " . (isset($_ENV['DB_USER']) ? $_ENV['DB_USER'] : 'NON DÉFINI') . "\n";
echo "DB_PASSWORD: " . (isset($_ENV['DB_PASSWORD']) ? (strlen($_ENV['DB_PASSWORD']) > 0 ? '***DEFINI***' : 'VIDE') : 'NON DÉFINI') . "\n";
echo "APP_ENV: " . (isset($_ENV['APP_ENV']) ? $_ENV['APP_ENV'] : 'NON DÉFINI') . "\n";
echo "</pre>";

// Vérifier $_SERVER aussi
echo "<h2>Variables dans \$_SERVER :</h2>";
echo "<pre>";
echo "DB_HOST: " . (isset($_SERVER['DB_HOST']) ? $_SERVER['DB_HOST'] : 'NON DÉFINI') . "\n";
echo "DB_PORT: " . (isset($_SERVER['DB_PORT']) ? $_SERVER['DB_PORT'] : 'NON DÉFINI') . "\n";
echo "DB_NAME: " . (isset($_SERVER['DB_NAME']) ? $_SERVER['DB_NAME'] : 'NON DÉFINI') . "\n";
echo "DB_USER: " . (isset($_SERVER['DB_USER']) ? $_SERVER['DB_USER'] : 'NON DÉFINI') . "\n";
echo "DB_PASSWORD: " . (isset($_SERVER['DB_PASSWORD']) ? (strlen($_SERVER['DB_PASSWORD']) > 0 ? '***DEFINI***' : 'VIDE') : 'NON DÉFINI') . "\n";
echo "</pre>";

// Vérifier avec getenv()
echo "<h2>Variables avec getenv() :</h2>";
echo "<pre>";
echo "DB_HOST: " . (getenv('DB_HOST') ?: 'NON DÉFINI') . "\n";
echo "DB_PORT: " . (getenv('DB_PORT') ?: 'NON DÉFINI') . "\n";
echo "DB_NAME: " . (getenv('DB_NAME') ?: 'NON DÉFINI') . "\n";
echo "DB_USER: " . (getenv('DB_USER') ?: 'NON DÉFINI') . "\n";
echo "DB_PASSWORD: " . (getenv('DB_PASSWORD') ? (strlen(getenv('DB_PASSWORD')) > 0 ? '***DEFINI***' : 'VIDE') : 'NON DÉFINI') . "\n";
echo "</pre>";

// Tester la connexion
echo "<h2>Test de Connexion :</h2>";

// Charger les variables d'environnement si un fichier .env existe (pour développement local)
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue; // Ignorer les commentaires
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $_ENV[trim($name)] = trim($value);
        }
    }
}

// Récupération des variables d'environnement avec valeurs par défaut pour le développement local
$host = $_ENV['DB_HOST'] ?? $_SERVER['DB_HOST'] ?? getenv('DB_HOST') ?: '127.0.0.1';
$dbname = $_ENV['DB_NAME'] ?? $_SERVER['DB_NAME'] ?? getenv('DB_NAME') ?: 'fttx_project';
$username = $_ENV['DB_USER'] ?? $_SERVER['DB_USER'] ?? getenv('DB_USER') ?: 'root';
$password = $_ENV['DB_PASSWORD'] ?? $_SERVER['DB_PASSWORD'] ?? getenv('DB_PASSWORD') ?: '';
$port = $_ENV['DB_PORT'] ?? $_SERVER['DB_PORT'] ?? getenv('DB_PORT') ?: '3306';

echo "<pre>";
echo "Valeurs utilisées pour la connexion :\n";
echo "Host: $host\n";
echo "Port: $port\n";
echo "Database: $dbname\n";
echo "User: $username\n";
echo "Password: " . (strlen($password) > 0 ? '***DEFINI***' : 'VIDE') . "\n";
echo "</pre>";

try {
    // Tester la connexion
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8";
    echo "<p>Tentative de connexion avec : <code>$dsn</code></p>";
    
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<div style='background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<strong>✅ SUCCÈS !</strong> Connexion à la base de données réussie !";
    echo "</div>";
    
    // Tester une requête simple
    $stmt = $conn->query("SELECT VERSION() as version");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p><strong>Version MySQL :</strong> " . $result['version'] . "</p>";
    
} catch (PDOException $e) {
    echo "<div style='background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<strong>❌ ERREUR !</strong> Impossible de se connecter à la base de données.<br>";
    echo "<strong>Message d'erreur :</strong> " . htmlspecialchars($e->getMessage());
    echo "</div>";
    
    echo "<h3>Solutions possibles :</h3>";
    echo "<ul>";
    echo "<li>Vérifiez que les variables d'environnement sont bien configurées dans Render</li>";
    echo "<li>Vérifiez que votre base de données est créée et accessible</li>";
    echo "<li>Vérifiez que le host, port, nom de base, utilisateur et mot de passe sont corrects</li>";
    echo "<li>Vérifiez que votre base de données est dans la même région que votre application</li>";
    echo "</ul>";
}

echo "<hr>";
echo "<p><small>Ce fichier de test peut être supprimé après avoir résolu le problème.</small></p>";
?>

