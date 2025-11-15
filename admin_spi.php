<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Chef SPI') {
    header("Location: login.php");
    exit;
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require 'database.php';

// Fonction pour enregistrer les actions dans le journal
function logAction($conn, $admin_username, $action, $operation_id) {
    try {
        $query = $conn->prepare("INSERT INTO logs (admin_username, action, operation_id) VALUES (?, ?, ?)");
        $query->execute([$admin_username, $action, $operation_id]);
    } catch (PDOException $e) {
        echo "Erreur de journalisation : " . $e->getMessage();
    }
}

// Récupérer la liste des opérations existantes
$operations = $conn->query("SELECT operation_id FROM operations")->fetchAll(PDO::FETCH_COLUMN);

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Requête non valide. Token CSRF manquant ou incorrect.");
    }

    $operation_id = htmlspecialchars($_POST['operation_id']);

    if (isset($_POST['supprimer'])) {
        // Suppression de l'opération
        if (empty($operation_id)) {
            die("Erreur : Veuillez fournir l'ID de l'opération pour la suppression.");
        }

        $query = $conn->prepare("DELETE FROM operations WHERE operation_id = ?");
        $query->execute([$operation_id]);

        // Journalisation de la suppression
        logAction($conn, $_SESSION['username'], 'suppression', $operation_id);

        header("Location: admin_spi.php?success=delete");
        exit;
    } else {
        // Insertion ou mise à jour de l'opération
        $etat_spi = htmlspecialchars($_POST['etat_spi']);
        $montant_estime = htmlspecialchars($_POST['montant_estime']);
        if (!is_numeric($montant_estime) || $montant_estime < 0) {
            die("Le montant estimé doit être un nombre réel valide.");
        }
        $genie_civil = htmlspecialchars($_POST['genie_civil']);
        $date_sdr = htmlspecialchars($_POST['date_sdr']);

        // Lecture du fichier charte graphique
$charte_graphique_blob = null;
$charte_graphique_type = null;
if (isset($_FILES['charte_graphique']) && $_FILES['charte_graphique']['error'] == 0) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($_FILES['charte_graphique']['type'], $allowed_types)) {
        die("Le fichier doit être une image de type JPEG, PNG ou GIF.");
    }
    $charte_graphique_blob = file_get_contents($_FILES['charte_graphique']['tmp_name']);
    $charte_graphique_type = $_FILES['charte_graphique']['type'];
}

// Lecture du fichier devis
$devis_blob = null;
$devis_type = null;
if (isset($_FILES['devis']) && $_FILES['devis']['error'] == 0) {
    if ($_FILES['devis']['type'] != 'application/pdf') {
        die("Le fichier doit être au format PDF.");
    }
    $devis_blob = file_get_contents($_FILES['devis']['tmp_name']);
    $devis_type = $_FILES['devis']['type'];
}


        $check_query = $conn->prepare("SELECT COUNT(*) FROM operations WHERE operation_id = ?");
        $check_query->execute([$operation_id]);
        $count = $check_query->fetchColumn();

        if ($count > 0) {
            // Mise à jour de l'opération existante
            $query = $conn->prepare("UPDATE operations 
                SET etat_spi = ?, montant_estime = ?, genie_civil = ?, date_sdr = ?, 
                    charte_graphique_blob = ?, charte_graphique_type = ?, devis_blob = ?, devis_type = ? 
                WHERE operation_id = ?");
            $query->execute([
                $etat_spi, $montant_estime, $genie_civil, $date_sdr, 
                $charte_graphique_blob, $charte_graphique_type, $devis_blob, $devis_type, 
                $operation_id
            ]);

            // Journalisation de la mise à jour
            logAction($conn, $_SESSION['username'], 'mise à jour', $operation_id);

            header("Location: admin_spi.php?success=update");
        } else {
            // Insertion d'une nouvelle opération
            $query = $conn->prepare("INSERT INTO operations 
                (operation_id, etat_spi, montant_estime, genie_civil, date_sdr, 
                charte_graphique_blob, charte_graphique_type, devis_blob, devis_type) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $query->execute([
                $operation_id, $etat_spi, $montant_estime, $genie_civil, $date_sdr, 
                $charte_graphique_blob, $charte_graphique_type, $devis_blob, $devis_type
            ]);

            // Journalisation de l'insertion
            logAction($conn, $_SESSION['username'], 'insertion', $operation_id);

            header("Location: admin_spi.php?success=insert");
        }
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration SPI</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>

<div class="container mt-5">
    <header class="d-flex justify-content-between align-items-center mb-4">
        <h1>Ajouter, Modifier ou Supprimer une opération SPI</h1>
        <a href="logout.php" class="btn btn-danger">Déconnexion</a>
    </header>

    <!-- Bouton de retour à la page d'accueil -->
    <div class="mb-3">
        <a href="index.php" class="btn btn-secondary">Retour à la page d'accueil</a>
    </div>

    <?php 
    if (isset($_GET['success'])) {
        $messages = [
            'insert' => "Insertion de l'opération SPI réussie.",
            'update' => "Mise à jour de l'opération SPI réussie.",
            'delete' => "Suppression de l'opération SPI réussie."
        ];
        $msg_type = ($_GET['success'] == 'delete') ? 'danger' : 'success';
        echo "<div class='alert alert-$msg_type'>{$messages[$_GET['success']]}</div>";
    }
    ?>

    <form action="admin_spi.php" method="POST" enctype="multipart/form-data" id="operationForm">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <!-- Liste déroulante des opérations avec recherche dynamique -->
        <div class="form-group">
            <label for="operation_id">ID de l'Opération :</label>
            <input list="operation_list" class="form-control" name="operation_id" id="operation_id" required>
            <datalist id="operation_list">
                <?php foreach ($operations as $op_id): ?>
                    <option value="<?php echo $op_id; ?>">
                <?php endforeach; ?>
            </datalist>
        </div>

        <!-- Les champs suivants ne sont requis que pour Enregistrer/Modifier -->
        <div id="fieldsWrapper">
            <div class="form-group">
                <label for="etat_spi">État SPI :</label>
                <select class="form-control" name="etat_spi" id="etat_spi">
                    <option value="En cours">En cours</option>
                    <option value="Achevé">Achevé</option>
                    <option value="En instance">En instance</option>
                </select>
            </div>
            <div class="form-group">
                <label for="montant_estime">Montant Estimé (en DT) :</label>
                <input type="number" step="any" class="form-control" name="montant_estime" id="montant_estime">
            </div>
            <div class="form-group">
                <label for="genie_civil">Génie Civil :</label>
                <select class="form-control" name="genie_civil" id="genie_civil">
                    <option value="Oui">Oui</option>
                    <option value="Non">Non</option>
                </select>
            </div>
            <div class="form-group">
                <label for="date_sdr">Date SDR :</label>
                <input type="date" class="form-control" name="date_sdr" id="date_sdr">
            </div>
            <div class="form-group">
                <label for="charte_graphique">Charte Graphique (image) :</label>
                <input type="file" class="form-control" name="charte_graphique" id="charte_graphique">
            </div>
            <div class="form-group">
                <label for="devis">Devis (PDF) :</label>
                <input type="file" class="form-control" name="devis" id="devis">
            </div>
        </div>

        <!-- Boutons d'action -->
        <button type="submit" name="enregistrer" class="btn btn-primary">Enregistrer</button>
        <button type="submit" name="supprimer" class="btn btn-danger" id="deleteButton" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette opération ?')">Supprimer</button>
    </form>
</div>

<script>
    // Gestion dynamique des champs obligatoires
    $(document).ready(function() {
        $('#deleteButton').on('click', function() {
            // Supprime les attributs "required" des champs pour la suppression
            $('#fieldsWrapper input, #fieldsWrapper select').removeAttr('required');
        });

        $('#operationForm').on('submit', function() {
            if (!$('input[name="operation_id"]').val()) {
                alert('Veuillez sélectionner un ID d\'opération.');
                return false;
            }
        });

        // Filtre dynamique pour la liste des opérations
        $('#operation_id').on('input', function() {
            let filter = $(this).val().toLowerCase();
            $('#operation_list option').each(function() {
                let text = $(this).val().toLowerCase();
                $(this).toggle(text.indexOf(filter) > -1);
            });
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>



