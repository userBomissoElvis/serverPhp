<?php
// session_start();
// require 'db.php';

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $email = $_POST['email'];
//     $password = $_POST['password'];

//     try {
//         $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE email = ?");
//         $stmt->execute([$email]);
//         $user = $stmt->fetch(PDO::FETCH_ASSOC);

//         if ($user && password_verify($password, $user['password'])) {
//             // Connexion réussie : on stocke les infos en session
//             $_SESSION['user_id'] = $user['id'];
//             $_SESSION['username'] = $user['username'];

//             echo "Connexion réussie ! Bonjour, " . $_SESSION['username'];
//             echo '<br><a href="logout.php">Déconnexion</a>';
//         } else {
//             echo "Email ou mot de passe incorrect.";
//         }
//     } catch (PDOException $e) {
//         die("Erreur : " . $e->getMessage());
//     }
// } else {
//     echo "Méthode non autorisée.";
// }






// session_start();
// require 'db.php';

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $email = $_POST['email'];
//     $password = $_POST['password'];

//     try {
//         $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE email = ?");
//         $stmt->execute([$email]);
//         $user = $stmt->fetch(PDO::FETCH_ASSOC);

//         if ($user && password_verify($password, $user['password'])) {
//             // Génération d'un token unique
//             $token = bin2hex(random_bytes(32));

//             // Stockage du token dans la base de données
//             $update = $pdo->prepare("UPDATE users SET token = ? WHERE id = ?");
//             $update->execute([$token, $user['id']]);

//             // Affichage du token
//             echo json_encode([
//                 "message" => "Connexion réussie",
//                 "token" => $token
//             ]);
//         } else {
//             echo json_encode(["error" => "Email ou mot de passe incorrect"]);
//         }
//     } catch (PDOException $e) {
//         die(json_encode(["error" => $e->getMessage()]));
//     }
// } else {
//     echo json_encode(["error" => "Méthode non autorisée"]);
// }

session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Génération d'un token unique
            $token = bin2hex(random_bytes(32));
          //   $expiry = date('Y-m-d H:i:s', strtotime('+1 hour')); // Expire dans 1 heure
               $expiry = date('Y-m-d H:i:s', strtotime('+90 seconds')); // Expire dans 30 secondes


            // Stockage du token et de son expiration
            $update = $pdo->prepare("UPDATE users SET token = ?, token_expiry = ? WHERE id = ?");
            $update->execute([$token, $expiry, $user['id']]);

            // Réponse JSON
            echo json_encode([
                "message" => "Connexion réussie",
                "token" => $token,
                "expires_at" => $expiry
            ]);
        } else {
            echo json_encode(["error" => "Email ou mot de passe incorrect"]);
        }
    } catch (PDOException $e) {
        die(json_encode(["error" => $e->getMessage()]));
    }
} else {
    echo json_encode(["error" => "Méthode non autorisée"]);
}
?>
