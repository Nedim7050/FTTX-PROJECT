<?php
require 'database.php';

$operation_id = intval($_GET['id']);
$type = $_GET['type']; // 'charte' ou 'devis'

$query = $conn->prepare("SELECT charte_graphique_blob, devis_blob FROM operations WHERE operation_id = ?");
$query->execute([$operation_id]);
$result = $query->fetch(PDO::FETCH_ASSOC);

if ($type === 'charte' && !empty($result['charte_graphique_blob'])) {
    header("Content-Type: image/jpeg"); // Adaptez si nécessaire
    echo $result['charte_graphique_blob'];
} elseif ($type === 'devis' && !empty($result['devis_blob'])) {
    header("Content-Type: application/pdf");
    echo $result['devis_blob'];
} else {
    http_response_code(404);
    echo "Fichier non trouvé.";
}
?>
