<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Chef Commission Réception') {
    header("Location: login.php");
    exit;
}

// Génération du token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require 'database.php';

// Fonction pour journaliser l'action d'administration
function logAction($conn, $admin_username, $action, $operation_id) {
    try {
        $query = $conn->prepare("INSERT INTO logs (admin_username, action, operation_id) VALUES (?, ?, ?)");
        $query->execute([$admin_username, $action, $operation_id]);
    } catch (PDOException $e) {
        echo "Erreur de journalisation : " . $e->getMessage();
    }
}


// Traitement du formulaire avec validation des entrées
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Requête non valide. Token CSRF manquant ou incorrect.");
    }

    $operation_id = htmlspecialchars($_POST['operation_id']);
    $date_reception = htmlspecialchars($_POST['date_reception']);
    $decision = htmlspecialchars($_POST['decision']);

    // Vérification des valeurs entrées
    if (empty($operation_id)) {
        die("Erreur : L'ID de l'opération est requis.");
    }
    if (!in_array($decision, ['Ajourné', 'Réceptionné'])) {
        die("Valeur invalide pour la décision.");
    }
    

    try {
        // Mise à jour de l'opération dans la table centralisée "operations"
        $query = $conn->prepare("UPDATE operations SET date_reception = ?, decision = ? WHERE operation_id = ?");
        $query->execute([$date_reception, $decision, $operation_id]);

        // Journaliser l'action de mise à jour
        logAction($conn, $_SESSION['username'], 'Mise à jour de l\'opération', $operation_id);

        header("Location: admin_commission_reception.php?success=update");
        exit;
    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage();
    }
}

// Récupération des opérations existantes pour la liste déroulante
$operations = $conn->query("SELECT operation_id FROM operations")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commission de Réception</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" /> <!-- Select2 CSS -->
</head>
<body>

<div class="container mt-5">
    <header class="d-flex justify-content-between align-items-center mb-4">
        <h1>Modifier une opération - Commission de Réception</h1>
        <a href="logout.php" class="btn btn-danger">Déconnexion</a>
    </header>

    <!-- Bouton de retour à la page d'accueil -->
    <div class="mb-3">
        <a href="index.php" class="btn btn-secondary">Retour à la page d'accueil</a>
    </div>

    <?php 
    if (isset($_GET['success']) && $_GET['success'] == 'update') {
        echo "<div class='alert alert-success'>Mise à jour réussie pour l'opération.</div>";
    }
    ?>

    <form action="admin_commission_reception.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <!-- Liste déroulante pour l'ID de l'opération avec recherche -->
        <div class="form-group">
            <label for="operation_id">Sélectionnez une Opération :</label>
            <select class="form-control" name="operation_id" id="operation_id" required>
                <option value="">-- Choisissez une opération --</option>
                <?php foreach ($operations as $operation) : ?>
                    <option value="<?php echo htmlspecialchars($operation['operation_id']); ?>">
                        <?php echo htmlspecialchars($operation['operation_id']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="date_reception">Date de Réception :</label>
            <input type="date" class="form-control" name="date_reception" id="date_reception" required>
        </div>
        <div class="form-group">
        <label for="decision">Décision :</label>
<select class="form-control" name="decision" id="decision" required>
    <option value="Ajourné">Ajourné</option>
    <option value="Réceptionné">Réceptionné</option>
</select>
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>
</div>

<!-- Inclusion des scripts Select2 -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"></script>

<script>
    // Initialisation de Select2 pour l'ID de l'Opération avec la fonctionnalité de recherche
    $(document).ready(function() {
        $('#operation_id').select2({
            placeholder: 'Sélectionnez une opération',
            allowClear: true,
            width: '100%'
        });
    });
</script>

</body>
</html>
