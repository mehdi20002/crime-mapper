<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'citoyen') {
    header('Location: login.php');
    exit();
}

require 'tools/config.php'; // Assurez-vous que ce fichier contient votre connexion à la base de données
include 'layout/header-login.php';

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare('SELECT prenom, nom, cin, phone, address FROM users WHERE id = ?');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$stmt->bind_result($prenom, $nom, $cin, $phone, $address);
$stmt->fetch();
$stmt->close();

$nom_complet = $prenom . ' ' . $nom;
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Citoyen</title>
</head>

<body>
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-6 mx-auto border shadow p-4">
                <h1>Profile Citoyen</h1>
                <p><strong>Nom d'utilisateur:</strong> <?php echo htmlspecialchars($nom_complet); ?></p>
                <p><strong>CIN:</strong> <?php echo htmlspecialchars($cin); ?></p>
                <p><strong>Téléphone:</strong> <?php echo htmlspecialchars($phone); ?></p>
                <p><strong>Adresse:</strong> <?php echo htmlspecialchars($address); ?></p>
                <a href="map/map.php">Accéder à la carte</a>
            </div>
        </div>
    </div>
</body>

</html>
