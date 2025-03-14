<?php
// session_start();
// require 'db.php';

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $username = $_POST['username'];
//     $email = $_POST['email'];
//     $password = $_POST['password'];
//     $role = $_POST['role'];

//     // Validation des champs
//     if (empty($username) || empty($email) || empty($password) || empty($role)) {
//         die(json_encode(["error" => "Tous les champs sont obligatoires."]));
//     }

//     // Vérifier si l'email existe déjà
//     $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
//     $stmt->execute([$email]);
//     if ($stmt->rowCount() > 0) {
//         die(json_encode(["error" => "Cet email est déjà utilisé."]));
//     }

//     // Hachage du mot de passe
//     $hashed_password = password_hash($password, PASSWORD_ARGON2ID);

//     try {
//         // Enregistrement dans la base de données
//         $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
//         $stmt->execute([$username, $email, $hashed_password, $role]);

//         echo json_encode(["message" => "Utilisateur inscrit avec succès."]);
//     } catch (PDOException $e) {
//         die(json_encode(["error" => $e->getMessage()]));
//     }
// } else {
//     echo json_encode(["error" => "Méthode non autorisée."]);
// }

// session_start();
// require 'db.php';

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $username = $_POST['username'];
//     $email = $_POST['email'];
//     $password = $_POST['password'];
//     $role = $_POST['role'];

//     // Validation des champs
//     if (empty($username) || empty($email) || empty($password) || empty($role)) {
//         die(json_encode(["error" => "Tous les champs sont obligatoires."]));
//     }

//     // Vérifier si l'email existe déjà
//     $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
//     $stmt->execute([$email]);
//     if ($stmt->rowCount() > 0) {
//         die(json_encode(["error" => "Cet email est déjà utilisé."]));
//     }

//     // Hachage du mot de passe
//     $hashed_password = password_hash($password, PASSWORD_ARGON2ID);

//     // Générer un token d'activation unique
//     $activation_token = bin2hex(random_bytes(50));
//     $expiry = date('Y-m-d H:i:s', strtotime('+1 day')); // Token valide pendant 1 jour

//     try {
//         // Enregistrement de l'utilisateur dans la base de données avec token d'activation
//         $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role, token, token_expiry) VALUES (?, ?, ?, ?, ?, ?)");
//         $stmt->execute([$username, $email, $hashed_password, $role, $activation_token, $expiry]);

//         // Envoi de l'email avec le lien d'activation
//         $activation_link = "http://localhost/activate.php?token=" . $activation_token;
//         $subject = "Activation de votre compte";
//         $message = "Bonjour $username,\n\nPour activer votre compte, cliquez sur le lien suivant :\n$activation_link\n\nL'équipe.";
//         $headers = "From: bomissoelvis63@gmail.com";

//         mail($email, $subject, $message, $headers);

//         echo json_encode(["message" => "Utilisateur inscrit avec succès. Un email d'activation a été envoyé."]);
//     } catch (PDOException $e) {
//         die(json_encode(["error" => $e->getMessage()]));
//     }
// } else {
//     echo json_encode(["error" => "Méthode non autorisée."]);
// }


// Démarrer la session
session_start();

// Inclure le fichier PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Assure-toi que le chemin est correct
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

    // Validation de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die(json_encode(["error" => "L'adresse email n'est pas valide."]));
    }

    // Vérifier si l'email existe déjà
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        die(json_encode(["error" => "Cet email est déjà utilisé."]));
    }

    // Hachage du mot de passe
    $hashed_password = password_hash($password, PASSWORD_ARGON2ID);

    // Générer un token d'activation unique
    $activation_token = bin2hex(random_bytes(50));
    $expiry = date('Y-m-d H:i:s', strtotime('+1 day')); // Token valide pendant 1 jour

    try {
        // Enregistrement de l'utilisateur dans la base de données avec token d'activation
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role, token, token_expiry) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$username, $email, $hashed_password, $role, $activation_token, $expiry]);

        // Créer l'email d'activation
        $activation_link = "http://localhost/activate.php?token=" . $activation_token;
        $subject = "Activation de votre compte";
        $message = "Bonjour $username,\n\nPour activer votre compte, cliquez sur le lien suivant :\n$activation_link\n\nL'équipe.";

        // Utiliser PHPMailer pour envoyer l'email
        $mail = new PHPMailer(true);
        $monMail = 'bomissoelvis63@gmail.com';
        $monPasswordMail = 'bOm1$$O1919'; // Utilise un mot de passe d'application
        try {
            // Configuration du serveur SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Serveur SMTP de Gmail
            $mail->SMTPAuth = true;
            $mail->Username = $monMail;
            $mail->Password = $monPasswordMail;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Définir l'expéditeur
            $mail->setFrom($monMail, 'Ton Site');
            
            // Ajouter le destinataire
            $mail->addAddress($email, $username);
            
            // Sujet de l'email
            $mail->Subject = $subject;
            
            // Contenu de l'email
            $mail->Body = $message;

            // Envoi de l'email
            $mail->send();

            echo json_encode(["message" => "Utilisateur inscrit avec succès. Un email d'activation a été envoyé."]);
        } catch (Exception $e) {
            die(json_encode(["error" => "Erreur lors de l'envoi de l'email : {$mail->ErrorInfo}"]));
        }
    } catch (PDOException $e) {
        die(json_encode(["error" => $e->getMessage()])); 
    }
} else {
    echo json_encode(["error" => "Méthode non autorisée."]);
}
?>
