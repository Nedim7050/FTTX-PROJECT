<?php
session_start();
require 'database.php';

// Vérifiez si l'utilisateur est connecté et obtenez son rôle
$isAuthenticated = isset($_SESSION['username']);
$role = $isAuthenticated ? $_SESSION['role'] : null;

// Liste des colonnes du tableau
$colonnes = [
    'ID Opération' => 'operation_id',
    'Charte Graphique' => 'charte_graphique_blob',
    'Etat SPI' => 'etat_spi',
    'GC (génie civil)' => 'genie_civil',
    'Montant estimé (DT)' => 'montant_estime',
    'Devis' => 'devis_blob',
    'Date SDR' => 'date_sdr',
    'Date Ordre de service' => 'date_ordre_service',
    'Entreprise' => 'entreprise',
    'Etat d\'avancement (%)' => 'etat_avancement',
    'Observation' => 'observation',
    'Montant de réalisation (DT)' => 'montant_realisation',
    'Date de Réception' => 'date_reception',
    'Décision' => 'decision',
    'Récupération de l\'ancien Réseau' => 'recuperation_reseau',
    'N.PARC' => 'n_parc'
];

// Gestion de la recherche
$searchQuery = '';
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchQuery = htmlspecialchars($_GET['search']);
}

function afficherTableau($conn, $colonnes, $page, $rowsPerPage, $searchQuery) {
    if (!$conn) {
        die("Erreur : Connexion à la base de données échouée.");
    }

    $offset = ($page - 1) * $rowsPerPage;

    // Requête avec recherche
    $sql = "SELECT * FROM operations";
    $params = [];
    if (!empty($searchQuery)) {
        $searchTerms = '%' . $searchQuery . '%';
        $sql .= " WHERE operation_id LIKE :search OR entreprise LIKE :search OR observation LIKE :search";
        $params['search'] = $searchTerms;
    }
    $sql .= " LIMIT :offset, :rows";

    $query = $conn->prepare($sql);
    foreach ($params as $key => $value) {
        $query->bindValue(":$key", $value, PDO::PARAM_STR);
    }
    $query->bindValue(':offset', $offset, PDO::PARAM_INT);
    $query->bindValue(':rows', $rowsPerPage, PDO::PARAM_INT);
    $query->execute();

    $resultats = $query->fetchAll(PDO::FETCH_ASSOC);

    // Barre de défilement en haut
    echo "<div id='scrollTopContainer'>";
    echo "<div id='scrollTop' class='custom-scroll'></div>";
    echo "</div>";

    // Tableau principal
    echo "<div class='table-responsive fade-in'>";
    echo "<table id='operationsTable' class='table custom-table'>";
    echo "<thead><tr>";
    foreach ($colonnes as $colonne_titre => $colonne_nom) {
        echo "<th>$colonne_titre</th>";
    }
    echo "</tr></thead><tbody>";

    foreach ($resultats as $ligne) {
        echo "<tr>";
        foreach ($colonnes as $colonne_titre => $colonne_nom) {
            if ($colonne_nom === 'charte_graphique_blob' && !empty($ligne[$colonne_nom])) {
                // Afficher l'image en base64
                $imageData = base64_encode($ligne[$colonne_nom]);
                $mimeType = htmlspecialchars($ligne['charte_graphique_type']);
                echo "<td class='text-center'>
                          <a href='data:$mimeType;base64,$imageData' download='charte_graphique'>
                              <img src='data:$mimeType;base64,$imageData' alt='Charte Graphique' class='img-thumbnail' style='max-width: 100px;'>
                          </a>
                      </td>";
            } elseif ($colonne_nom === 'devis_blob' && !empty($ligne[$colonne_nom])) {
                // Afficher le lien pour le PDF
                $pdfData = base64_encode($ligne[$colonne_nom]);
                echo "<td class='text-center'>
                          <a href='data:application/pdf;base64,$pdfData' download='devis.pdf' class='btn btn-sm btn-info'>Voir PDF</a>
                      </td>";
                    } elseif ($colonne_nom === 'observation' && !empty($ligne[$colonne_nom])) {
                        $observation = htmlspecialchars($ligne[$colonne_nom]);
                        $shortObservation = mb_substr($observation, 0, 300); // Limite à 300 caractères
                        $observationId = uniqid("obs_"); // Génère un ID unique
                        echo "<td class='observation-cell'>
                                  <div id='$observationId'>
                                      <span class='short-text'>$shortObservation...</span>
                                      <span class='full-text' style='display: none;'>$observation</span>
                                      <button class='show-more' onclick='toggleObservation(\"$observationId\")'>Voir plus</button>
                                  </div>
                              </td>";
                    
                    } else {
                // Affichage par défaut
                echo "<td>" . htmlspecialchars($ligne[$colonne_nom] ?? '') . "</td>";
            }
        }
        echo "</tr>";
    }
    echo "</tbody></table>";
    echo "</div>";
     

}



// Pagination Logic
function getTotalPages($conn, $rowsPerPage, $searchQuery) {
    $sql = "SELECT COUNT(*) FROM operations";
    $params = [];
    if (!empty($searchQuery)) {
        $sql .= " WHERE operation_id LIKE :search OR entreprise LIKE :search OR observation LIKE :search";
        $params['search'] = '%' . $searchQuery . '%';
    }
    $query = $conn->prepare($sql);
    foreach ($params as $key => $value) {
        $query->bindValue(":$key", $value, PDO::PARAM_STR);
    }
    $query->execute();
    $totalRows = $query->fetchColumn();
    return ceil($totalRows / $rowsPerPage);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Suivi des Opérations FTTx</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>

        /* Conteneur pour la barre de défilement en haut */
#scrollTopContainer {
    width: 100%;
    overflow-x: auto;
    margin-bottom: 10px;
}

#scrollTop {
    height: 1px; /* Une petite hauteur suffit pour le scrolling */
    background: transparent;
    width: 2000px; /* Ajuster à la largeur estimée du tableau */
}
        .custom-table td {
            word-wrap: break-word;
            white-space: normal;
        }

        /* Styles */
        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('/mnt/data/background 5.png');
            background-size: cover;
            background-position: center;
            filter: blur(10px);
            z-index: -1;
        }
   /* Style spécifique pour la cellule "Observation" */
   .custom-table tbody .observation-cell {
            text-align: justify;
            vertical-align: top;
            white-space: normal;
            word-wrap: break-word;
            overflow-wrap: break-word;
            max-width: 900px;
            min-width: 600px;
            font-size: 16px;
            padding: 10px;
            line-height: 1.6;
            margin: 0;
            font-family: Arial, sans-serif;
            vertical-align: top;
        }
        .observation-cell .short-text,
    .observation-cell .full-text {
        display: block; /* Chaque texte occupe sa propre ligne */
    }

    .observation-cell button.show-more {
        margin-top: 5px; /* Ajoute un espace entre le bouton et le texte */
        padding: 5px 10px; /* Améliore la taille du bouton */
        font-size: 0.9rem; /* Ajuste la taille du texte du bouton */
        background-color: transparent; /* Bouton sans fond pour qu'il s'intègre mieux */
        color: #007bff; /* Couleur bleue pour le bouton */
        border: none; /* Supprime les bordures */
        cursor: pointer; /* Change le curseur en pointeur pour indiquer que c'est cliquable */
    }

    .observation-cell button.show-more:hover {
        text-decoration: underline; /* Ajoute un soulignement au survol pour indiquer une action */
    }
        .content-wrapper {
            position: relative;
            z-index: 1;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
        }

        .title {
            color: #1a202c;
            font-weight: 700;
            padding: 20px;
            text-align: center;
            font-size: 28px;
            background-color: rgba(255, 255, 255, 0.9);
            border-bottom: 2px solid #4a5568;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            margin-top: 10px;
            border-radius: 8px;
            overflow: hidden;
        }

        .custom-table thead th {
            white-space: nowrap;
            background-color: #2c5282;
            color: white;
            font-size: 16px;
            font-weight: 500;
            padding: 18px;
            text-transform: uppercase;
            text-align: center;
            vertical-align: middle;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .custom-table tbody td {
            padding: 20px;
            font-size: 18px;
            white-space: nowrap;
            text-align: center;
            vertical-align: middle;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination a {
            color: #2c5282;
            padding: 10px 15px;
            border: 1px solid #2c5282;
            margin: 0 5px;
            border-radius: 5px;
        }

        .pagination a.active {
            background-color: #2c5282;
            color: white;
        }

        .filter-container {
            text-align: right;
            margin-bottom: 15px;
        }

        .filter-container input {
            width: 300px;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="content-wrapper">
    <div class="title">Tableau de Bord : Suivi des Opérations FTTx</div>
    <div class="container-fluid table-container">

        <!-- Barre de recherche -->
        <div class="filter-container mb-3 d-flex justify-content-end">
            <form action="dashboard.php" method="GET">
                <input type="text" name="search" class="form-control" style="width: 300px;" placeholder="Rechercher dans le tableau..." value="<?php echo htmlspecialchars($searchQuery); ?>">
            </form>
        </div>

        <!-- Bouton de retour à la page d'accueil -->
        <div class="mb-3">
            <a href="index.php" class="btn btn-secondary">Retour à la page d'accueil</a>
        </div>

        <?php
        // Initialisation de la pagination
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $rowsPerPage = 10;
        $totalPages = getTotalPages($conn, $rowsPerPage, $searchQuery);

        // Affichage du tableau avec pagination
        afficherTableau($conn, $colonnes, $page, $rowsPerPage, $searchQuery);
        ?>

        <!-- Affichage de la pagination -->
        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($searchQuery); ?>" class="<?php if ($i == $page) echo 'active'; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Récupération des éléments de défilement
        var scrollTop = document.getElementById("scrollTopContainer");
        var tableContainer = document.querySelector(".table-responsive");

        // Synchronisation des barres de défilement
        scrollTop.addEventListener("scroll", function() {
            tableContainer.scrollLeft = scrollTop.scrollLeft;
        });

        tableContainer.addEventListener("scroll", function() {
            scrollTop.scrollLeft = tableContainer.scrollLeft;
        });

        // Ajuster la largeur de la barre en haut pour correspondre au tableau
        var scrollTopBar = document.getElementById("scrollTop");
        var table = document.getElementById("operationsTable");
        scrollTopBar.style.width = table.scrollWidth + "px";
    });
</script>
<script>
        function toggleObservation(id) {
            const container = document.getElementById(id);
            const shortText = container.querySelector('.short-text');
            const fullText = container.querySelector('.full-text');
            const button = container.querySelector('.show-more');

            if (fullText.style.display === 'none') {
                shortText.style.display = 'none';
                fullText.style.display = 'inline';
                button.textContent = 'Voir moins';
            } else {
                shortText.style.display = 'inline';
                fullText.style.display = 'none';
                button.textContent = 'Voir plus';
            }
        }
    </script>;


<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
