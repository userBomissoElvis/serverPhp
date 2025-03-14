<?php
require 'db.php';

try {
    $stmt = $pdo->query("SELECT id, username, email FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<pre>";
    print_r($users);
    echo "</pre>";
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>
