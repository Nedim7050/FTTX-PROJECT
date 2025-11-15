<?php
require 'database.php'; // S'assure que la connexion $conn est établie

// Liste des utilisateurs avec les mots de passe en clair et les rôles
$users = [
    ['username' => 'chef_spi', 'password' => 'spi123', 'role' => 'Chef SPI'],
    ['username' => 'chef_sdr', 'password' => 'sdr123', 'role' => 'Chef SDR'],
    ['username' => 'chef_reception', 'password' => 'reception123', 'role' => 'Chef Commission Réception'],
    ['username' => 'chef_csc_bn', 'password' => 'cscbn123', 'role' => 'Chef CSC Banlieue Nord'],
    ['username' => 'chef_csc_bardo', 'password' => 'cscbardo123', 'role' => 'Chef CSC Bardo'],
    ['username' => 'chef_csc_belvedere', 'password' => 'cscbelvedere123', 'role' => 'Chef CSC Belvédère'],
    ['username' => 'chef_csc_kasba', 'password' => 'csckasba123', 'role' => 'Chef CSC Kasba'],
    ['username' => 'chef_csc_hachad', 'password' => 'cschachad123', 'role' => 'Chef CSC Hachad'],
];

try {
    // Préparation de la requête d'insertion
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
    
    // Insertion de chaque utilisateur avec un mot de passe haché
    foreach ($users as $user) {
        $hashed_password = password_hash($user['password'], PASSWORD_DEFAULT);
        $stmt->execute([
            ':username' => $user['username'],
            ':password' => $hashed_password,
            ':role' => $user['role'],
        ]);
        echo "Utilisateur {$user['username']} ajouté avec succès.<br>";
    }
    echo "Tous les utilisateurs ont été ajoutés avec des mots de passe hachés.";

} catch (PDOException $e) {
    echo "Erreur lors de l'insertion des utilisateurs : " . $e->getMessage();
}
?>
