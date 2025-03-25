<?php
require_once 'config.php';

// Enregistrer la déconnexion avant de détruire la session
if (isLoggedIn()) {
    $session_id = getCurrentSessionId();
    if ($session_id) {
        recordLogout($session_id);
    }
}

// Détruire la session
session_destroy();

// Rediriger vers la page de connexion
header("Location: index.php");
exit();
?>