<?php
// Inclure le fichier autoload de Composer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Assure-toi que ce chemin est correct

// Fonction pour envoyer un email d'activation
function sendActivationEmail($userEmail, $username, $activationLink) {
    $mail = new PHPMailer(true);
    try {
        // Configuration du serveur SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Serveur SMTP de Gmail
        $mail->SMTPAuth = true;
        $Username = 'bomissoelvis1919@gmail.com';

        // Utilisation de variables d'environnement pour sécuriser les informations sensibles
        $mail->Username = $Username; // Utilisation de la variable d'environnement pour l'email
        $mail->Password = 'bOmI$$O?ElvI$.163'; // Utilisation de la variable d'environnement pour le mot de passe
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Définir l'expéditeur
        $mail->setFrom('bomissoelvis1919@gmail.com', 'Ton Site'); // Utilisation de la variable d'environnement pour l'email expéditeur
        
        // Ajouter le destinataire
        $mail->addAddress($userEmail, $username);
        
        // Sujet de l'email
        $mail->Subject = 'Activation de votre compte';
        
        // Contenu de l'email
        $mail->Body = "Bonjour $username,\n\nPour activer votre compte, cliquez sur le lien suivant :\n$activationLink\n\nL'équipe.";

        // Envoi de l'email
        $mail->send();
        echo 'Message envoyé';
    } catch (Exception $e) {
        echo "Message non envoyé. Erreur : {$mail->ErrorInfo}";
    }
}

// Exemple d'utilisation de la fonction
$userEmail = 'destinataire@example.com'; // Remplacer par l'email de l'utilisateur
$username = 'BomissoElvis'; // Remplacer par le nom d'utilisateur
$activationLink = 'http://localhost/activate.php?token=abcdef12345'; // Lien d'activation

sendActivationEmail($userEmail, $username, $activationLink);
?>
