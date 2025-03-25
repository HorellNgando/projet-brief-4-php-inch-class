<?php
require_once 'config.php';

// Fonction pour enregistrer un nouvel utilisateur
function registerUser($username, $email, $password, $role_id = 2, $status = 'active') {
    global $pdo;
    
    // Vérifier si l'utilisateur existe déjà
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->rowCount() > 0) {
        return false; // Email déjà utilisé
    }
    
    // Hasher le mot de passe
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    
    // Insérer le nouvel utilisateur
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role_id, status) VALUES (?, ?, ?, ?, ?)");
    $success = $stmt->execute([$username, $email, $hashedPassword, $role_id, $status]);
    
    if ($success) {
        // Enregistrer la session de connexion
        $user_id = $pdo->lastInsertId();
        recordLogin($user_id);
        return $user_id;
    }
    
    return false;
}

// Fonction pour connecter un utilisateur
function loginUser($email, $password) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT users.*, roles.name as role_name FROM users JOIN roles ON users.role_id = roles.id WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        if ($user['status'] === 'inactive') {
            return 'compte_inactif';
        }
        
        // Mettre à jour la session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role_name'];
        
        // Enregistrer la connexion
        recordLogin($user['id']);
        
        return true;
    }
    
    return false;
}

// Fonction pour enregistrer une connexion
function recordLogin($user_id) {
    global $pdo;
    
    $stmt = $pdo->prepare("INSERT INTO sessions (user_id, login_time) VALUES (?, NOW())");
    $stmt->execute([$user_id]);
    return $pdo->lastInsertId();
}

// Fonction pour obtenir les logs de connexion/déconnexion
function getSessionLogs($user_id = null) {
    global $pdo;
    
    $query = "SELECT s.*, u.username, u.email as user_email FROM sessions s JOIN users u ON s.user_id = u.id";
    
    if ($user_id) {
        $query .= " WHERE s.user_id = ?";
    }
    
    $query .= " ORDER BY s.login_time DESC";
    
    $stmt = $pdo->prepare($query);
    
    if ($user_id) {
        $stmt->execute([$user_id]);
    } else {
        $stmt->execute();
    }
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour obtenir tous les utilisateurs
function getAllUsers() {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT u.*, r.name as role_name FROM users u JOIN roles r ON u.role_id = r.id ORDER BY u.created_at DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour obtenir un utilisateur par ID
function getUserById($id) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT u.*, r.name as role_name FROM users u JOIN roles r ON u.role_id = r.id WHERE u.id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fonction pour obtenir un utilisateur par email
function getUserByEmail($email) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT u.*, r.name as role_name FROM users u JOIN roles r ON u.role_id = r.id WHERE u.email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fonction pour mettre à jour un utilisateur
function updateUser($id, $username, $email, $role_id, $status) {
    global $pdo;
    
    $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, role_id = ?, status = ? WHERE id = ?");
    return $stmt->execute([$username, $email, $role_id, $status, $id]);
}

// Fonction pour supprimer un utilisateur
function deleteUser($id) {
    global $pdo;
    
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    return $stmt->execute([$id]);
}

// Fonction pour obtenir tous les rôles
function getAllRoles() {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM roles");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour créer un utilisateur (pour l'admin)
function createUser($username, $email, $password, $role_id, $status = 'active') {
    global $pdo;
    
    // Vérifier si l'email existe déjà
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->rowCount() > 0) {
        return false; // Email déjà utilisé
    }
    
    // Hasher le mot de passe
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    
    // Insérer le nouvel utilisateur
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role_id, status) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$username, $email, $hashedPassword, $role_id, $status]);
}

// Ajouter cette fonction à auth.php
function searchUsers($searchTerm, $filterBy = 'all') {
    global $pdo;
    
    $query = "SELECT u.*, r.name as role_name 
              FROM users u 
              JOIN roles r ON u.role_id = r.id 
              WHERE 1=1";
    
    $params = [];
    
    if (!empty($searchTerm)) {
        switch ($filterBy) {
            case 'name':
                $query .= " AND u.username LIKE ?";
                $params[] = "%$searchTerm%";
                break;
            case 'email':
                $query .= " AND u.email LIKE ?";
                $params[] = "%$searchTerm%";
                break;
            case 'status':
                $query .= " AND u.status = ?";
                $params[] = $searchTerm;
                break;
            case 'role':
                $query .= " AND r.name = ?";
                $params[] = $searchTerm;
                break;
            default: // 'all'
                $query .= " AND (u.username LIKE ? OR u.email LIKE ? OR u.status = ? OR r.name LIKE ?)";
                $params[] = "%$searchTerm%";
                $params[] = "%$searchTerm%";
                $params[] = $searchTerm;
                $params[] = "%$searchTerm%";
                break;
        }
    }
    
    $query .= " ORDER BY u.created_at DESC";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>