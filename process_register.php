<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Validation des champs
    if (empty($username) || empty($email) || empty($password) || empty($role)) {
        die(json_encode(["error" => "Tous les champs sont obligatoires."]));
    }

    // Vérifier si l'email existe déjà
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        die(json_encode(["error" => "Cet email est déjà utilisé."]));
    }

    // Hachage du mot de passe
    $hashed_password = password_hash($password, PASSWORD_ARGON2ID);

    try {
        // Enregistrement dans la base de données
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $email, $hashed_password, $role]);

        echo json_encode(["message" => "Utilisateur inscrit avec succès."]);
    } catch (PDOException $e) {
        die(json_encode(["error" => $e->getMessage()]));
    }
} else {
    echo json_encode(["error" => "Méthode non autorisée."]);
}
?>
