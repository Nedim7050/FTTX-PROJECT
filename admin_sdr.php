<?php
session_start();

// Vérification d'authentification et du rôle
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Chef SDR') {
    header("Location: login.php");
    exit;
}

// Génération du token CSRF pour sécurité
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


// Récupération des opérations existantes pour la liste déroulante
$operation_list = $conn->query("SELECT operation_id FROM operations")->fetchAll(PDO::FETCH_COLUMN);

// Traitement du formulaire avec validation des entrées
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Requête non valide. Token CSRF manquant ou incorrect.");
    }

    // Récupération et validation des données POST
    $operation_id = htmlspecialchars($_POST['operation_id']);
    $date_ordre_service = htmlspecialchars($_POST['date_ordre_service']);
    $entreprise = htmlspecialchars($_POST['entreprise']);
    $etat_avancement = htmlspecialchars($_POST['etat_avancement']);
    if (!is_numeric($etat_avancement) || $etat_avancement < 0 || $etat_avancement > 100) {
        die("L'état d'avancement doit être un pourcentage valide.");
    }
    
    $observation = htmlspecialchars($_POST['observation']);
    $montant_realisation = htmlspecialchars($_POST['montant_realisation']);
    if (!is_numeric($montant_realisation) || $montant_realisation < 0) {
        die("Le montant de réalisation doit être un nombre réel valide.");
    }

    if (empty($operation_id)) {
        die("Erreur : L'ID de l'opération est requis.");
    }

    // Mise à jour des informations pour une opération existante uniquement
    try {
        $query = $conn->prepare("UPDATE operations SET 
            date_ordre_service = :date_ordre_service, 
            entreprise = :entreprise, 
            etat_avancement = :etat_avancement, 
            observation = :observation, 
            montant_realisation = :montant_realisation 
            WHERE operation_id = :operation_id");

        $query->bindValue(':operation_id', $operation_id, PDO::PARAM_STR);
        $query->bindValue(':date_ordre_service', $date_ordre_service);
        $query->bindValue(':entreprise', $entreprise);
        $query->bindValue(':etat_avancement', $etat_avancement);
        $query->bindValue(':observation', $observation);
        $query->bindValue(':montant_realisation', $montant_realisation);
        $query->execute();

        // Journaliser l'action de mise à jour
        logAction($conn, $_SESSION['username'], 'Mise à jour de l\'opération', $operation_id);

        header("Location: admin_sdr.php?success=update");
        exit;
    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration SDR</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" /> <!-- Select2 CSS -->
</head>
<body>

<div class="container mt-5">
    <header class="d-flex justify-content-between align-items-center mb-4">
        <h1>Modifier une opération SDR</h1>
        <a href="logout.php" class="btn btn-danger">Déconnexion</a>
    </header>

    <!-- Bouton de retour à la page d'accueil -->
    <div class="mb-3">
        <a href="index.php" class="btn btn-secondary">Retour à la page d'accueil</a>
    </div>

    <?php 
    if (isset($_GET['success'])) {
        echo "<div class='alert alert-success'>Mise à jour réussie pour l'opération.</div>";
    }
    ?>

    <form action="admin_sdr.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <!-- Liste déroulante dynamique pour l'ID de l'Opération avec recherche -->
        <div class="form-group">
            <label for="operation_id">ID de l'Opération :</label>
            <select class="form-control" name="operation_id" id="operation_id" required>
                <option value="" disabled selected>Sélectionnez une opération</option>
                <?php foreach ($operation_list as $op_id) : ?>
                    <option value="<?php echo htmlspecialchars($op_id); ?>"><?php echo htmlspecialchars($op_id); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="date_ordre_service">Date Ordre de Service :</label>
            <input type="date" class="form-control" name="date_ordre_service" id="date_ordre_service">
        </div>
        <div class="form-group">
            <label for="entreprise">Entreprise :</label>
            <input type="text" class="form-control" name="entreprise" id="entreprise">
        </div>
        <div class="form-group">
            <label for="etat_avancement">État d'Avancement (%) :</label>
            <input type="number" class="form-control" name="etat_avancement" id="etat_avancement" step="any">

        </div>
        <div class="form-group">
            <label for="observation">Observation :</label>
            <input type="text" class="form-control" name="observation" id="observation">
        </div>
        <div class="form-group">
            <label for="montant_realisation">Montant de Réalisation (en DT) :</label>
            <input type="number" step="any" class="form-control" name="montant_realisation" id="montant_realisation" required>
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
