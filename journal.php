<?php
session_start();
if (!isset($_SESSION['journal_admin'])) {
    header("Location: login_journal.php");
    exit;
}

require 'database.php';

// Pagination logic
$logsPerPage = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $logsPerPage;

// Search by date
$dateFilter = '';
$params = ['offset' => $offset, 'rows' => $logsPerPage];

if (!empty($_GET['date'])) {
    $dateFilter = "WHERE DATE(timestamp) = :date";
    $params['date'] = $_GET['date'];
}

// Fetch logs
$query = $conn->prepare("SELECT * FROM logs $dateFilter ORDER BY timestamp DESC LIMIT :offset, :rows");
foreach ($params as $key => $value) {
    $query->bindValue(":$key", $value, $key === 'offset' || $key === 'rows' ? PDO::PARAM_INT : PDO::PARAM_STR);
}
$query->execute();
$logs = $query->fetchAll(PDO::FETCH_ASSOC);

// Count total logs for pagination
$totalLogsQuery = $conn->prepare("SELECT COUNT(*) FROM logs $dateFilter");
if (!empty($params['date'])) {
    $totalLogsQuery->bindValue(':date', $params['date']);
}
$totalLogsQuery->execute();
$totalLogs = $totalLogsQuery->fetchColumn();
$totalPages = ceil($totalLogs / $logsPerPage);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Journal des Actions</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Flatpickr CSS -->
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
        }
        .header-buttons {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .table-container {
            margin-top: 20px;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .pagination-container {
            margin-top: 20px;
        }
        .btn-custom {
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            color: white;
            border: none;
            font-size: 16px;
            padding: 10px 20px;
            border-radius: 25px;
            transition: 0.3s;
        }
        .btn-custom:hover {
            background: linear-gradient(135deg, #0072ff, #00c6ff);
            color: #fff;
        }
        .flatpickr-input {
            font-size: 16px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header-buttons">
        <h1>Journal des Actions</h1>
        <div>
            <a href="index.php" class="btn btn-custom">Retour à l'Accueil</a>
            <a href="login_journal.php" class="btn btn-danger">Déconnexion</a>
        </div>
    </div>

    <!-- Search Form -->
    <form method="GET" action="journal.php" class="d-flex justify-content-end">
        <input type="text" name="date" class="flatpickr-input me-2" placeholder="Choisir une date" value="<?= htmlspecialchars($_GET['date'] ?? '', ENT_QUOTES, 'UTF-8') ?>" data-input>
        <button type="submit" class="btn btn-custom">Rechercher</button>
    </form>

    <!-- Logs Table -->
    <div class="table-container">
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Admin</th>
                    <th>Action</th>
                    <th>Opération ID</th>
                    <th>Date et Heure</th>
                    <th>Supprimer</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($logs)) : ?>
                    <?php foreach ($logs as $log) : ?>
                        <tr>
                            <td><?= htmlspecialchars($log['admin_username'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($log['action'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($log['operation_id'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($log['timestamp'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <form method="POST" action="delete_log.php" style="display:inline;">
                                    <input type="hidden" name="log_id" value="<?= htmlspecialchars($log['id'], ENT_QUOTES, 'UTF-8') ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5" class="text-center">Aucun log trouvé</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <nav class="pagination-container">
        <ul class="pagination justify-content-center">
            <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="journal.php?page=<?= $page - 1 ?>&date=<?= htmlspecialchars($_GET['date'] ?? '', ENT_QUOTES, 'UTF-8') ?>">Précédent</a>
            </li>
            <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <a class="page-link" href="journal.php?page=<?= $i ?>&date=<?= htmlspecialchars($_GET['date'] ?? '', ENT_QUOTES, 'UTF-8') ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
            <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                <a class="page-link" href="journal.php?page=<?= $page + 1 ?>&date=<?= htmlspecialchars($_GET['date'] ?? '', ENT_QUOTES, 'UTF-8') ?>">Suivant</a>
            </li>
        </ul>
    </nav>
</div>

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr(".flatpickr-input", {
        dateFormat: "Y-m-d",
        allowInput: true,
        locale: "fr"
    });
</script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
