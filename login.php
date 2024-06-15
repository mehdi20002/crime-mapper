<?php
require 'tools/config.php';
include 'layout/header-login.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Vérification de la méthode de requête (POST)
    // Récupération des données du formulaire
    $cin = $_POST['cin'];
    $password = $_POST['password'];

    //la requête SQL pour récupérer l'utilisateur correspondant au CIN
    $stmt = $conn->prepare('SELECT id, mot_de_passe, role FROM users WHERE cin = ?');
    if (!$stmt) {
        die('Erreur de préparation: ' . $conn->error);
    }
    $stmt->bind_param('s', $cin); // Liaison du paramètre CIN à la requête SQL
    $stmt->execute();// Exécution de la requête
    $stmt->bind_result($id, $hashed_password, $role);
    $stmt->fetch();
    $stmt->close();

    // Vérifier si l'utilisateur existe
    if ($id) {
        // Vérification du mot de passe
        if (password_verify($password, $hashed_password)) {
            session_start();
            // Stockage des informations
            $_SESSION['user_id'] = $id;
            $_SESSION['role'] = $role;

            // Messages de débogage pour vérifier les valeurs
            echo 'User ID: ' . $_SESSION['user_id'] . '<br>';
            echo 'Role: ' . $_SESSION['role'] . '<br>';

            // Redirection en fonction du rôle
            if (strtolower($role) === 'policier') {
                header('Location: policier.php');
                exit();
            } else {
                header('Location: citoyen.php');
                exit();
            }
        } else {
            echo 'Mot de passe incorrect!';
        }
    } else {
        echo 'CIN incorrect!';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-6 mx-auto border shadow p-4">
                <h2 class="text-center mb-4">Connexion</h2>
                <hr />

                <form method="post" action="login.php">
                    <div class="form-group row">
                        <label for="cin" class="col-sm-4 col-form-label">CIN:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="cin" name="cin" required><br>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-sm-4 col-form-label">Mot de passe:</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" id="password" name="password" required><br>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="offset-sm-4 col-sm-8">
                            <button type="submit" class="btn btn-primary">Se connecter</button>
                            <a href="/index.php" class="btn btn-outline-primary">Annuler</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
