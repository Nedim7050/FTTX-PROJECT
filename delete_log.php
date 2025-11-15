<?php
require 'database.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['log_id'])) {
    $log_id = (int) $_POST['log_id'];
    $query = $conn->prepare("DELETE FROM logs WHERE id = ?");
    $query->execute([$log_id]);
    header('Location: journal.php');
    exit;
}
?>
