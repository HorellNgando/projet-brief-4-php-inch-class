<?php
require_once 'config.php';
require_once 'auth.php';

redirectIfNotLoggedIn();

// Récupérer les logs selon le rôle de l'utilisateur
if (isAdmin()) {
    $logs = getSessionLogs();
    $title = "Historique des sessions";
} else {
    $logs = getSessionLogs($_SESSION['user_id']);
    $title = "Mes sessions";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> - Gestion des Utilisateurs</title>
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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><?php echo $title; ?></h2>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <?php if (count($logs) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <?php if (isAdmin()): ?>
                                                <th>Utilisateur</th>
                                                <th>Email</th>
                                            <?php endif; ?>
                                            <th>Connexion</th>
                                            <th>Déconnexion</th>
                                            <th>Durée</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($logs as $log): ?>
                                            <tr>
                                                <?php if (isAdmin()): ?>
                                                    <td><?php echo htmlspecialchars($log['username']); ?></td>
                                                    <td><?php echo htmlspecialchars($log['user_email']); ?></td>
                                                <?php endif; ?>
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
                        <?php else: ?>
                            <p>Aucune session enregistrée.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>