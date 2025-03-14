<?php
// require 'db.php'; // Inclusion du fichier de connexion

// $username = "john_doe";
// $email = "john@example.com";
// $password = "password123"; // Mot de passe en clair

// // Hashage du mot de passe
// $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// try {
//     $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
//     $stmt->execute([$username, $email, $hashedPassword]);
//     echo "Utilisateur inséré avec succès !";
// } catch (PDOException $e) {
//     die("Erreur : " . $e->getMessage());
// }

require 'db.php';

$username = "TestUser";
$email = "test@example.com";
$password = "MonMotDePasse123";

// Hachage sécurisé du mot de passe
$hashed_password = password_hash($password, PASSWORD_ARGON2ID);

$stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
$stmt->execute([$username, $email, $hashed_password]);

echo "Utilisateur ajouté avec un mot de passe sécurisé.";
?>
