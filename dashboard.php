<?php
require_once 'config.php';
require_once 'auth.php';

redirectIfNotLoggedIn();

$user = getUserById($_SESSION['user_id']);

// Si admin, récupérer les statistiques
if (isAdmin()) {
    $totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $activeUsers = $pdo->query("SELECT COUNT(*) FROM users WHERE status = 'active'")->fetchColumn();
    $inactiveUsers = $pdo->query("SELECT COUNT(*) FROM users WHERE status = 'inactive'")->fetchColumn();
    $totalSessions = $pdo->query("SELECT COUNT(*) FROM sessions")->fetchColumn();
    
    // Traitement de l'ajout d'utilisateur
    if (isset($_POST['add_user'])) {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $role_id = intval($_POST['role_id']);
        $status = $_POST['status'];
        
        if (createUser($username, $email, $password, $role_id, $status)) {
            header("Location: dashboard.php?user_added=1");
            exit();
        } else {
            $error = "L'email est déjà utilisé ou une erreur s'est produite.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Gestion des Utilisateurs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3">
                <?php include 'includes/sidebar.php'; ?>
            </div>
            <div class="col-md-9">
                <h2 class="mb-4">Tableau de Bord</h2>
                
                <?php if (isset($_GET['user_added'])): ?>
                    <div class="alert alert-success">Utilisateur ajouté avec succès !</div>
                <?php elseif (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if (isAdmin()): ?>
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Utilisateurs</h5>
                                    <p class="card-text display-6"><?php echo $totalUsers; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Actifs</h5>
                                    <p class="card-text display-6"><?php echo $activeUsers; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-dark">
                                <div class="card-body">
                                    <h5 class="card-title">Inactifs</h5>
                                    <p class="card-text display-6"><?php echo $inactiveUsers; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Sessions</h5>
                                    <p class="card-text display-6"><?php echo $totalSessions; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Formulaire d'ajout rapide d'utilisateur -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Ajouter un utilisateur</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="username" class="form-label">Nom d'utilisateur</label>
                                        <input type="text" class="form-control" id="username" name="username" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="password" class="form-label">Mot de passe</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="role_id" class="form-label">Rôle</label>
                                        <select class="form-select" id="role_id" name="role_id" required>
                                            <?php foreach (getAllRoles() as $role): ?>
                                                <option value="<?php echo $role['id']; ?>"><?php echo htmlspecialchars($role['name']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="status" class="form-label">Statut</label>
                                        <select class="form-select" id="status" name="status" required>
                                            <option value="active">Actif</option>
                                            <option value="inactive">Inactif</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 d-flex align-items-end">
                                        <button type="submit" name="add_user" class="btn btn-primary w-100">Ajouter</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Dernières sessions</h5>
                        </div>
                        <div class="card-body">
                            <?php 
                            $logs = getSessionLogs();
                            if (count($logs) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Utilisateur</th>
                                                <th>Email</th>
                                                <th>Connexion</th>
                                                <th>Déconnexion</th>
                                                <th>Durée</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach (array_slice($logs, 0, 5) as $log): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($log['username']); ?></td>
                                                    <td><?php echo htmlspecialchars($log['user_email']); ?></td>
                                                    <td><?php echo date('d/m/Y H:i', strtotime($log['login_time'])); ?></td>
                                                    <td>
                                                        <?php if ($log['logout_time']): ?>
                                                            <?php echo date('d/m/Y H:i', strtotime($log['logout_time'])); ?>
                                                        <?php else: ?>
                                                            <span class="badge bg-warning">En cours</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($log['session_duration']): ?>
                                                            <?php echo gmdate('H\h i\m s\s', $log['session_duration']); ?>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <a href="logs.php" class="btn btn-sm btn-outline-primary">Voir tout</a>
                            <?php else: ?>
                                <p>Aucune session enregistrée.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Bienvenue, <?php echo htmlspecialchars($user['username']); ?> !</h5>
                            <p class="card-text">Vous êtes connecté en tant que client.</p>
                            <p class="card-text">Email : <?php echo htmlspecialchars($user['email']); ?></p>
                            <p class="card-text">Dernière connexion : 
                                <?php 
                                $lastLogin = getSessionLogs($_SESSION['user_id']);
                                if (count($lastLogin) > 0) {
                                    echo date('d/m/Y à H:i', strtotime($lastLogin[0]['login_time']));
                                } else {
                                    echo "Première connexion";
                                }
                                ?>
                            </p>
                            <a href="profile.php" class="btn btn-primary">Mon profil</a>
                            <a href="logs.php" class="btn btn-outline-secondary">Mes sessions</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>