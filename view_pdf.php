<?php
require 'database.php';

if (!isset($_GET['id'])) {
    die("ID manquant.");
}

$id = intval($_GET['id']);
$query = $conn->prepare("SELECT devis FROM operations WHERE operation_id = ?");
$query->execute([$id]);
$result = $query->fetch(PDO::FETCH_ASSOC);

if (!$result || !$result['devis']) {
    die("Fichier introuvable.");
}

header('Content-Type: application/pdf');
echo base64_decode($result['devis']);
exit;
?>
