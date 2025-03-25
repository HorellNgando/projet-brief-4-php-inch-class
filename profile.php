<?php
require_once 'config.php';
require_once 'auth.php';

redirectIfNotLoggedIn();

$user = getUserById($_SESSION['user_id']);
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);
    
    // Validation
    if (empty($username)) {
        $errors['username'] = "Le nom d'utilisateur est requis.";
    }
    
    if (empty($email)) {
        $errors['email'] = "L'email est requis.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "L'email n'est pas valide.";
    }
    
    // Vérifier si le mot de passe doit être changé
    $change_password = !empty($new_password) || !empty($confirm_password);
    
    if ($change_password) {
        if (empty($current_password)) {
            $errors['current_password'] = "Le mot de passe actuel est requis pour changer le mot de passe.";
        } elseif (!password_verify($current_password, $user['password'])) {
            $errors['current_password'] = "Le mot de passe actuel est incorrect.";
        }
        
        if (empty($new_password)) {
            $errors['new_password'] = "Le nouveau mot de passe est requis.";
        } elseif (strlen($new_password) < 6) {
            $errors['new_password'] = "Le nouveau mot de passe doit contenir au moins 6 caractères.";
        }
        
        if ($new_password !== $confirm_password) {
            $errors['confirm_password'] = "Les nouveaux mots de passe ne correspondent pas.";
        }
    }
    
    // Si pas d'erreurs, mettre à jour le profil
    if (empty($errors)) {
        // Préparer la requête de mise à jour
        if ($change_password) {
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?");
            $success = $stmt->execute([$username, $email, $hashed_password, $_SESSION['user_id']]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
            $success = $stmt->execute([$username, $email, $_SESSION['user_id']]);
        }
        
        if ($success) {
            // Mettre à jour les informations de session
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $user = getUserById($_SESSION['user_id']);
            $success = true;
        } else {
            $errors['general'] = "Une erreur s'est produite lors de la mise à jour du profil.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - Gestion des Utilisateurs</title>
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
                <h2 class="mb-4">Mon Profil</h2>
                
                <?php if ($success): ?>
                    <div class="alert alert-success">Profil mis à jour avec succès !</div>
                <?php elseif (isset($errors['general'])): ?>
                    <div class="alert alert-danger"><?php echo $errors['general']; ?></div>
                <?php endif; ?>
                
                <div class="card">
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Nom d'utilisateur</label>
                                <input type="text" class="form-control <?php echo isset($errors['username']) ? 'is-invalid' : ''; ?>" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>">
                                <?php if (isset($errors['username'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['username']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
                                <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['email']; ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <h5 class="mt-4">Changer le mot de passe</h5>
                            <p class="text-muted">Remplissez uniquement si vous souhaitez changer votre mot de passe</p>
                            
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Mot de passe actuel</label>
                                <input type="password" class="form-control <?php echo isset($errors['current_password']) ? 'is-invalid' : ''; ?>" id="current_password" name="current_password">
                                <?php if (isset($errors['current_password'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['current_password']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <label for="new_password" class="form-label">Nouveau mot de passe</label>
                                <input type="password" class="form-control <?php echo isset($errors['new_password']) ? 'is-invalid' : ''; ?>" id="new_password" name="new_password">
                                <?php if (isset($errors['new_password'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['new_password']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirmer le nouveau mot de passe</label>
                                <input type="password" class="form-control <?php echo isset($errors['confirm_password']) ? 'is-invalid' : ''; ?>" id="confirm_password" name="confirm_password">
                                <?php if (isset($errors['confirm_password'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['confirm_password']; ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>