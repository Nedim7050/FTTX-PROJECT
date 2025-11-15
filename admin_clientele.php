<?php
session_start();

// Vérification d'authentification et du rôle pour les chefs CSC
if (!isset($_SESSION['username']) || !in_array($_SESSION['role'], [
    'Chef CSC Banlieue Nord', 'Chef CSC Bardo', 'Chef CSC Belvédère', 'Chef CSC Kasba', 'Chef CSC Hachad'
])) {
    header("Location: login.php");
    exit;
}

// Génération du token CSRF pour la sécurité
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
    $recuperation_reseau = htmlspecialchars($_POST['recuperation_reseau']);
    $n_parc = htmlspecialchars($_POST['n_parc']);

    if (empty($operation_id)) {
        die("Erreur : L'ID de l'opération est requis.");
    }
    if (!in_array($recuperation_reseau, ['Oui', 'Non'])) {
        die("Valeur invalide pour la récupération du réseau. Valeurs acceptées : Oui ou Non.");
    }
    if (!is_numeric($n_parc) || $n_parc < 0) {
        die("Le numéro de parc doit être un nombre positif.");
    }

    try {
        // Mise à jour de l'opération existante dans la table "operations"
        $query = $conn->prepare("UPDATE operations SET 
            recuperation_reseau = :recuperation_reseau, 
            n_parc = :n_parc 
            WHERE operation_id = :operation_id");

        $query->bindValue(':operation_id', $operation_id, PDO::PARAM_STR);
        $query->bindValue(':recuperation_reseau', $recuperation_reseau);
        $query->bindValue(':n_parc', $n_parc);
        $query->execute();

        // Journaliser l'action de mise à jour
        logAction($conn, $_SESSION['username'], 'Mise à jour de l\'opération', $operation_id);

        header("Location: admin_clientele.php?success=update");
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
    <title>Administration Clientèle</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" /> <!-- Select2 CSS -->
</head>
<body>

<div class="container mt-5">
    <header class="d-flex justify-content-between align-items-center mb-4">
        <h1>Modifier une opération Clientèle</h1>
        <a href="logout.php" class="btn btn-danger">Déconnexion</a>
    </header>

    <!-- Bouton de retour à la page d'accueil -->
    <div class="mb-3">
        <a href="index.php" class="btn btn-secondary">Retour à la page d'accueil</a>
    </div>

    <!-- Message de succès -->
    <?php 
    if (isset($_GET['success']) && $_GET['success'] == 'update') {
        echo "<div class='alert alert-success'>Mise à jour réussie pour l'opération.</div>";
    }
    ?>

    <form action="admin_clientele.php" method="POST">
        <!-- Champ caché pour le token CSRF -->
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <!-- Liste déroulante avec recherche rapide pour l'ID de l'Opération -->
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
    <label for="recuperation_reseau">Récupération de l'ancien Réseau :</label>
    <select class="form-control" name="recuperation_reseau" id="recuperation_reseau" required>
        <option value="Oui">Oui</option>
        <option value="Non">Non</option>
    </select>
</div>

        <div class="form-group">
            <label for="n_parc">N.PARC :</label>
            <input type="number" class="form-control" name="n_parc" id="n_parc" required>
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>
</div>

<!-- Inclusion des scripts Select2 pour la recherche -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"></script>

<script>
    // Initialisation de Select2 pour la recherche rapide dans la liste déroulante
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
