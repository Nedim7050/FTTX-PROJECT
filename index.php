<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi du Marché FTTx</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Corps global */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Navigation */
        .navbar {
            background-color: white;
            padding: 10px 20px; /* Réduction de la hauteur */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand img {
            height: 100px; /* Logo agrandi */
        }

        .nav-link {
            color: #003580 !important;
            font-weight: bold;
        }

        .nav-link:hover {
            color: #0056b3 !important;
        }

        /* Titre animé */
        .hero-title {
            font-size: 3.5rem;
            font-weight: bold;
            color: #003580;
            animation: fadeInDown 2s;
            margin-bottom: 5px;
        }

        .hero-subtitle {
            font-size: 1rem;
            font-weight: normal;
            color: #666;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Slides */
        .carousel-inner img {
            width: 100%;
            height: 75vh;
            object-fit: cover;
        }

        .carousel .carousel-item {
            transition: transform 1.5s ease, opacity 1.5s ease; /* Animation douce */
        }

        /* Boutons sous le carrousel */
        .hero-buttons {
            margin-top: 20px;
        }

        .hero-buttons .btn {
            margin: 10px;
            font-size: 18px;
            padding: 12px 30px;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .hero-buttons .btn-primary {
            background-color: #003580;
            border-color: #003580;
        }

        .hero-buttons .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .hero-buttons .btn-secondary:hover {
            background-color: #e6e6e6;
        }
        /* Vidéo */
        .video-section {
            margin: 50px 0;
            text-align: center;
        }

        .video-section video {
            max-width: 80%;
            border: 5px solid #003580;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        /* Section Features */
        .features {
            background-color: #f8f9fa;
            padding: 50px 15px;
        }

        .features h3 {
            color: #003580;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .features p {
            font-size: 16px;
            color: #555;
        }

        /* Pied de page */
        .footer {
            background-color: #003580;
            color: white;
            text-align: center;
            padding: 15px 0;
            position: relative;
        }

        .footer img {
            height: 20px;
            margin-left: 10px;
            vertical-align: middle;
        }
    </style>
</head>
<body>

<!-- Barre de navigation -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="uploads/logo1.jpg" alt="Logo Tunisie Télécom">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Tableau de bord</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">S'identifier</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login_journal.php">Suivi des Actions</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Titre principal -->
<div class="text-center py-4">
    <h1 class="hero-title">Bienvenue dans le Site web de Suivi du Marché FTTx</h1>
    <p class="hero-subtitle">(Centre Urbain Nord de Tunis)</p>
</div>

<!-- Carousel -->
<div id="carouselExample" class="carousel slide" data-ride="carousel" data-interval="3000">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="uploads/background 5.png" alt="Slide 1">
        </div>
        <div class="carousel-item">
            <img src="uploads/background 8.jpg" alt="Slide 2">
        </div>
        <div class="carousel-item">
            <img src="uploads/background 10.png" alt="Slide 3">
        </div>
    </div>
    <a class="carousel-control-prev" href="#carouselExample" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Précédent</span>
    </a>
    <a class="carousel-control-next" href="#carouselExample" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Suivant</span>
    </a>
</div>

<!-- Boutons sous le carrousel -->
<div class="text-center hero-buttons">
    <a href="dashboard.php" class="btn btn-primary">Consulter le Tableau de Bord</a>
    <a href="login.php" class="btn btn-secondary">S'identifier</a>
</div>

<!-- Vidéo -->
<div class="video-section">
    <video controls>
        <source src="uploads/vid tt.mp4" type="video/mp4">
        Votre navigateur ne supporte pas la lecture vidéo.
    </video>
</div>

<!-- Section d'informations -->
<section class="features">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h3>Suivi en temps réel</h3>
                <p>Consultez l'état d'avancement des projets à tout moment.</p>
            </div>
            <div class="col-md-4">
                <h3>Accès Sécurisé</h3>
                <p>Connectez-vous pour gérer ou consulter les informations en toute sécurité.</p>
            </div>
            <div class="col-md-4">
                <h3>Données Fiables</h3>
                <p>Des données précises mises à jour par les administrateurs.</p>
            </div>
        </div>
    </div>
</section>

<!-- Pied de page -->
<footer class="footer">
    &copy; 2024 Tunisie Télécom. Tous droits réservés.
    <img src="uploads/logo1.jpg" alt="Logo Tunisie Télécom">
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
