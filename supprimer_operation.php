<?php
session_start();
require 'database.php';

if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

// Vérification si la requête est POST et que l'operation_id est fourni
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['operation_id'])) {
    $operation_id = htmlspecialchars($_POST['operation_id']);

    // Suppression de l'opération dans la table "operations"
    $query = $conn->prepare("DELETE FROM operations WHERE operation_id = ?");
    $query->execute([$operation_id]);

    // Vérifier si la suppression a réussi
    if ($query->rowCount() > 0) {
        // Redirection après suppression avec message de succès
        header("Location: admin_spi.php?success=delete");
        exit;
    } else {
        // Message d'erreur si l'ID de l'opération n'a pas été trouvé
        header("Location: admin_spi.php?error=not_found");
        exit;
    }
} else {
    // Redirige avec un message d'erreur si l'operation_id est manquant
    header("Location: admin_spi.php?error=missing_id");
    exit;
}
?>
