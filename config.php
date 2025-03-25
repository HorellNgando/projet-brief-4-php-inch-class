<?php
// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'user_management');

// Connexion à la base de données
try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Initialisation de la session
session_start();

// Fonction pour vérifier si l'utilisateur est connecté
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Fonction pour vérifier si l'utilisateur est admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Fonction pour rediriger si non connecté
function redirectIfNotLoggedIn() {
    if (!isLoggedIn()) {
        header("Location: index.php");
        exit();
    }
}

// Fonction pour rediriger si non admin
function redirectIfNotAdmin() {
    redirectIfNotLoggedIn();
    if (!isAdmin()) {
        header("Location: dashboard.php");
        exit();
    }
}

// Fonction pour enregistrer la déconnexion
function recordLogout($session_id) {
    global $pdo;
    
    $stmt = $pdo->prepare("UPDATE sessions SET logout_time = NOW(), session_duration = TIMESTAMPDIFF(SECOND, login_time, NOW()) WHERE id = ?");
    $stmt->execute([$session_id]);
}

// Fonction pour obtenir l'ID de la session actuelle
function getCurrentSessionId() {
    global $pdo;
    
    if (!isset($_SESSION['user_id'])) return null;
    
    $stmt = $pdo->prepare("SELECT id FROM sessions WHERE user_id = ? AND logout_time IS NULL ORDER BY login_time DESC LIMIT 1");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetchColumn();
}
?>