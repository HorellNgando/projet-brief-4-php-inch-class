<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs - Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            padding: 5rem 0;
            margin-bottom: 3rem;
        }
        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #2575fc;
        }
        .cta-buttons .btn {
            margin: 0 0.5rem;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
        }
        .card {
            transition: transform 0.3s;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .card:hover {
            transform: translateY(-10px);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="home.html">User Management</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Connexion</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Inscription</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-4">Gestion des Utilisateurs Simplifiée</h1>
            <p class="lead mb-5">Une solution complète pour administrer vos utilisateurs en toute sécurité</p>
            <div class="cta-buttons">
                <a href="index.php" class="btn btn-light btn-lg">Se connecter</a>
                <a href="register.php" class="btn btn-outline-light btn-lg">S'inscrire</a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="container mb-5">
        <div class="row text-center">
            <div class="col-md-4">
                <div class="card h-100 p-4">
                    <div class="feature-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                    <h3>Sécurité Renforcée</h3>
                    <p>Authentification sécurisée avec hachage bcrypt et gestion des sessions protégées.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 p-4">
                    <div class="feature-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <h3>Gestion Complète</h3>
                    <p>Créez, modifiez et gérez les comptes utilisateurs avec un système de rôles avancé.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 p-4">
                    <div class="feature-icon">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <h3>Tableaux de Bord</h3>
                    <p>Visualisez les statistiques et l'activité des utilisateurs en temps réel.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="bg-light py-5">
        <div class="container">
            <h2 class="text-center mb-5">Comment ça marche</h2>
            <div class="row align-items-center">
                <div class="col-md-6">
                    <img src="assets/image/OIP.jfif" alt="Interface démo" class="img-fluid rounded shadow">
                </div>
                <div class="col-md-6">
                    <div class="accordion" id="howItWorks">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#step1">
                                    1. Créez votre compte
                                </button>
                            </h2>
                            <div id="step1" class="accordion-collapse collapse show" data-bs-parent="#howItWorks">
                                <div class="accordion-body">
                                    Inscrivez-vous en quelques secondes pour accéder au système de gestion.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#step2">
                                    2. Gérez les utilisateurs
                                </button>
                            </h2>
                            <div id="step2" class="accordion-collapse collapse" data-bs-parent="#howItWorks">
                                <div class="accordion-body">
                                    Ajoutez, modifiez ou désactivez les comptes utilisateurs selon leurs rôles.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#step3">
                                    3. Suivez l'activité
                                </button>
                            </h2>
                            <div id="step3" class="accordion-collapse collapse" data-bs-parent="#howItWorks">
                                <div class="accordion-body">
                                    Consultez les logs de connexion et les statistiques d'utilisation.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-5 text-center">
        <div class="container">
            <h2 class="mb-4">Prêt à commencer?</h2>
            <p class="lead mb-4">Rejoignez-nous dès maintenant et simplifiez la gestion de vos utilisateurs</p>
            <a href="register.php" class="btn btn-primary btn-lg px-4">Créer un compte</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p class="mb-0">&copy; 202s UserManager. Tous droits réservés.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</body>
</html>