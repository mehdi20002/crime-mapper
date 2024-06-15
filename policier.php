<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'policier') {
    header('Location: login.php');
    exit();
}

require 'tools/config.php';
include 'layout/header-login.php';

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare('SELECT prenom, nom FROM users WHERE id = ?');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$stmt->bind_result($prenom, $nom);
$stmt->fetch();
$stmt->close();

$nom_complet = $prenom . ' ' . $nom;

// Récupérer les utilisateurs citoyens
$sql = "SELECT id, nom, prenom FROM users WHERE role = 'citoyen'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Policier</title>
    <!-- Intégration de Bootstrap via CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-6 mx-auto border shadow p-4">
                <h1 class="mb-4">Profile Policier</h1>
                <p><strong>Nom d'utilisateur:</strong> <?php echo htmlspecialchars($nom_complet); ?></p>
                <a href="map/map.php" class="btn mb-4" style="background-color: #007bff; color: white;">Accéder à la carte</a>
                
                <h2 class="mb-3">Liste des citoyens</h2>
                <div class="form-group">
                    <select id="citizen-select" class="form-control">
                        <option value="">Choisir un citoyen</option>
                        <?php
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['nom']) . " " . htmlspecialchars($row['prenom']) . "</option>";
                            }
                        } else {
                            echo "<option value=''>Aucun citoyen trouvé</option>";
                        }
                        ?>
                    </select>
                </div>
                <button id="download-pdf" class="btn" style="background-color: #007bff; color: white;">Télécharger PDF</button>
            </div>
        </div>
    </div>

    <!-- Intégration de jQuery et Bootstrap JS via CDN -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        document.getElementById('download-pdf').addEventListener('click', function() {
            const select = document.getElementById('citizen-select');
            const citizenId = select.value;
            if (citizenId) {
                window.location.href = 'download.php?id=' + citizenId;
            } else {
                alert('Veuillez choisir un citoyen.');
            }
        });
    </script>
</body>

</html>

<?php
$conn->close();
?>
