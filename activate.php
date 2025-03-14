<?php
require 'db.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Vérification du token dans la base de données
    $stmt = $pdo->prepare("SELECT * FROM users WHERE token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Vérifier si le token n'a pas expiré
        if (strtotime($user['token_expiry']) < time()) {
            echo "Le token a expiré. Veuillez demander un nouvel email d'activation.";
        } else {
            // Activer le compte en supprimant le token
            $stmt = $pdo->prepare("UPDATE users SET token = NULL, token_expiry = NULL WHERE id = ?");
            $stmt->execute([$user['id']]);

            echo "Votre compte a été activé avec succès. Vous pouvez maintenant vous connecter.";
        }
    } else {
        echo "Token invalide.";
    }
} else {
    echo "Aucun token trouvé.";
}
?>
