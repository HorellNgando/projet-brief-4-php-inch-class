<?php
require_once 'config.php';
require_once 'auth.php';

redirectIfNotAdmin();

// Récupérer les paramètres de recherche
$searchTerm = $_GET['search'] ?? '';
$filterBy = $_GET['filter'] ?? 'all';

// Recherche ou récupération de tous les utilisateurs
$users = !empty($searchTerm) 
    ? searchUsers($searchTerm, $filterBy) 
    : getAllUsers();

$roles = getAllRoles();
$errors = [];
$success = false;

// ... (le reste du code existant pour la gestion des utilisateurs) ...
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs - Admin</title>
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
                    <h2>Gestion des Utilisateurs</h2>
                    <a href="dashboard.php" class="btn btn-primary">Ajouter un utilisateur</a>
                </div>
                
                <!-- Formulaire de recherche -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-md-6">
                                <input type="text" name="search" class="form-control" placeholder="Rechercher..." 
                                       value="<?= htmlspecialchars($searchTerm) ?>">
                            </div>
                            <div class="col-md-3">
                                <select name="filter" class="form-select">
                                    <option value="all" <?= $filterBy === 'all' ? 'selected' : '' ?>>Tous les champs</option>
                                    <option value="name" <?= $filterBy === 'name' ? 'selected' : '' ?>>Nom</option>
                                    <option value="email" <?= $filterBy === 'email' ? 'selected' : '' ?>>Email</option>
                                    <option value="status" <?= $filterBy === 'status' ? 'selected' : '' ?>>Statut</option>
                                    <option value="role" <?= $filterBy === 'role' ? 'selected' : '' ?>>Rôle</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary w-100">Rechercher</button>
                                <?php if (!empty($searchTerm)): ?>
                                    <a href="users.php" class="btn btn-outline-secondary w-100 mt-2">Réinitialiser</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Affichage des résultats -->
                <div class="card">
                    <div class="card-body">
                        <?php if (!empty($searchTerm)): ?>
                            <div class="alert alert-info mb-3">
                                Résultats pour "<?= htmlspecialchars($searchTerm) ?>" 
                                (filtre : <?= htmlspecialchars($filterBy) ?>)
                                - <?= count($users) ?> résultat(s) trouvé(s)
                            </div>
                        <?php endif; ?>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom d'utilisateur</th>
                                        <th>Email</th>
                                        <th>Rôle</th>
                                        <th>Statut</th>
                                        <th>Créé le</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td><?= $user['id'] ?></td>
                                            <td><?= htmlspecialchars($user['username']) ?></td>
                                            <td><?= htmlspecialchars($user['email']) ?></td>
                                            <td><?= htmlspecialchars($user['role_name']) ?></td>
                                            <td>
                                                <span class="badge bg-<?= $user['status'] === 'active' ? 'success' : 'warning' ?>">
                                                    <?= $user['status'] === 'active' ? 'Actif' : 'Inactif' ?>
                                                </span>
                                            </td>
                                            <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="users.php?action=edit&id=<?= $user['id'] ?>" 
                                                       class="btn btn-outline-primary">Modifier</a>
                                                    <a href="users.php?toggle_status=<?= $user['id'] ?>" 
                                                       class="btn btn-outline-<?= $user['status'] === 'active' ? 'warning' : 'success' ?>">
                                                        <?= $user['status'] === 'active' ? 'Désactiver' : 'Activer' ?>
                                                    </a>
                                                    <?php if ($user['id'] !== $_SESSION['user_id']): ?>
                                                        <a href="users.php?delete=<?= $user['id'] ?>" 
                                                           class="btn btn-outline-danger" 
                                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                                            Supprimer
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>