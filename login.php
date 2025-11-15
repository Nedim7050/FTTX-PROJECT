<?php
session_start();
require 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    // Vérifie si l'utilisateur existe et récupère son rôle
    $query = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $query->execute([$username]);
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Authentification réussie
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirection en fonction du rôle
        switch ($_SESSION['role']) {
            case 'Chef SPI':
                header("Location: admin_spi.php");
                break;
            case 'Chef SDR':
                header("Location: admin_sdr.php");
                break;
            case 'Chef Commission Réception':
                header("Location: admin_commission_reception.php");
                break;
            case 'Chef CSC Banlieue Nord':
            case 'Chef CSC Bardo':
            case 'Chef CSC Belvédère':
            case 'Chef CSC Kasba':
            case 'Chef CSC Hachad':
                header("Location: admin_clientele.php");
                break;
            default:
                header("Location: dashboard.php");
                break;
        }
        exit;
    } else {
        $error_message = "Nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('uploads/background 5.png') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            overflow: hidden;
        }
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
        }
        .login-container {
            position: relative;
            z-index: 2;
            background: rgba(255, 255, 255, 0.95);
            padding: 50px;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
            width: 100%;
            max-width: 600px;
            text-align: center;
            margin-right: 5%;
            animation: slideIn 0.8s ease-out;
        }
        .login-container h1 {
            font-size: 30px;
            margin-bottom: 20px;
            font-weight: bold;
            color: #333;
        }
        .login-container .welcome-message {
            font-size: 18px;
            color: #666;
            margin-bottom: 20px;
        }
        .login-container input {
            width: 100%;
            padding: 15px;
            margin: 15px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            background: #f9f9f9;
        }
        .login-container input:focus {
            border-color: #0077b6;
            box-shadow: 0 0 8px rgba(0, 119, 182, 0.5);
        }
        .login-container button {
            width: 100%;
            background: #0077b6;
            color: #fff;
            border: none;
            padding: 15px;
            border-radius: 5px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s ease;
        }
        .login-container button:hover {
            background: #005f8e;
            transform: scale(1.05);
        }
        .login-container .back-btn {
            margin-top: 15px;
            display: inline-block;
            text-decoration: none;
            color: #0077b6;
            font-size: 14px;
        }
        .login-container .back-btn:hover {
            text-decoration: underline;
        }
        .login-container p {
            color: #e74c3c;
            margin-top: 15px;
        }
        .input-group {
            position: relative;
        }
        .input-group i {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: #aaa;
            font-size: 18px;
        }
        .input-group input {
            padding-left: 40px;
        }
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="login-container">
        <h1>Connexion</h1>
        <div class="welcome-message" id="welcomeMessage"></div>
        <form method="POST" action="login.php">
            <div class="input-group">
                <i class="bi bi-person-fill"></i>
                <input type="text" name="username" placeholder="Nom d'utilisateur" required>
            </div>
            <div class="input-group">
                <i class="bi bi-lock-fill"></i>
                <input type="password" name="password" placeholder="Mot de passe" required>
            </div>
            <button type="submit">Connexion</button>
            <?php if (isset($error_message)) echo "<p>$error_message</p>"; ?>
        </form>
        <a href="index.php" class="back-btn">Retour à la page d'accueil</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>
    <script>
        // Ajouter un message d'accueil dynamique
        const welcomeMessage = document.getElementById('welcomeMessage');
        const now = new Date();
        const hours = now.getHours();
        if (hours < 12) {
            welcomeMessage.textContent = "Bonjour, veuillez vous connecter pour continuer.";
        } else if (hours < 18) {
            welcomeMessage.textContent = "Bon après-midi, veuillez vous connecter pour continuer.";
        } else {
            welcomeMessage.textContent = "Bonsoir, veuillez vous connecter pour continuer.";
        }
    </script>
</body>
</html>
