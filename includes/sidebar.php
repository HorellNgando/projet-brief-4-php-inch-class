<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex flex-column align-items-center text-center">
            <img src="https://via.placeholder.com/100" alt="Avatar" class="rounded-circle mb-3" width="100">
            <div class="mb-3">
                <h5><?php echo htmlspecialchars($_SESSION['username']); ?></h5>
                <span class="badge bg-<?php echo isAdmin() ? 'primary' : 'success'; ?>">
                    <?php echo isAdmin() ? 'Administrateur' : 'Client'; ?>
                </span>
            </div>
        </div>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php">
                    <i class="bi bi-speedometer2 me-2"></i> Tableau de bord
                </a>
            </li>
            <?php if (isAdmin()): ?>
                <li class="nav-item">
                    <a class="nav-link" href="users.php">
                        <i class="bi bi-people me-2"></i> Gestion des utilisateurs
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logs.php">
                        <i class="bi bi-clock-history me-2"></i> Logs de connexion
                    </a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="profile.php">
                        <i class="bi bi-person me-2"></i> Mon profil
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logs.php">
                        <i class="bi bi-clock-history me-2"></i> Mes connexions
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>