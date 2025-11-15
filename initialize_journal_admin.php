<?php
// Connexion à la base de données
require 'database.php';

// Code d'insertion de l'admin, à exécuter une seule fois
$username = 'admin_journal';
$password = password_hash('journal_pass', PASSWORD_DEFAULT);

try {
    $insertAdminQuery = $conn->prepare("INSERT INTO journal_admin (username, password) VALUES (?, ?)");
    $insertAdminQuery->execute([$username, $password]);
    echo "Administrateur de journal ajouté avec succès.";
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
