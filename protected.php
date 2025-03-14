<?php
// require 'db.php';

// // Vérifier si un token est fourni
// if (!isset($_GET['token'])) {
//     die(json_encode(["error" => "Token manquant"]));
// }

// $token = $_GET['token'];

// try {
//     $stmt = $pdo->prepare("SELECT id, username FROM users WHERE token = ?");
//     $stmt->execute([$token]);
//     $user = $stmt->fetch(PDO::FETCH_ASSOC);

//     if ($user) {
//         echo json_encode([
//             "message" => "Accès autorisé",
//             "user" => $user
//         ]);
//     } else {
//         echo json_encode(["error" => "Token invalide"]);
//     }
// } catch (PDOException $e) {
//     die(json_encode(["error" => $e->getMessage()]));
// }


require 'db.php';

if (!isset($_GET['token'])) {
    die(json_encode(["error" => "Token manquant"]));
}

$token = $_GET['token'];

try {
    $stmt = $pdo->prepare("SELECT id, username, token_expiry FROM users WHERE token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (strtotime($user['token_expiry']) < time()) {
            die(json_encode(["error" => "Token expiré, reconnectez-vous."]));
        }

        echo json_encode([
            "message" => "Accès autorisé",
            "user" => $user
        ]);
    } else {
        echo json_encode(["error" => "Token invalide"]);
    }
} catch (PDOException $e) {
    die(json_encode(["error" => $e->getMessage()]));
}
?>
