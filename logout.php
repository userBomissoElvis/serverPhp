<?php
// session_start();
// session_destroy(); // Supprime toutes les sessions
// header("Location: login.php"); // Redirige vers la page de connexion
// exit;

require 'db.php';

if (!isset($_GET['token'])) {
    die(json_encode(["error" => "Token manquant"]));
}

$token = $_GET['token'];

try {
    $stmt = $pdo->prepare("UPDATE users SET token = NULL, token_expiry = NULL WHERE token = ?");
    $stmt->execute([$token]);

    echo json_encode(["message" => "Déconnexion réussie"]);
} catch (PDOException $e) {
    die(json_encode(["error" => $e->getMessage()]));
}
?>

