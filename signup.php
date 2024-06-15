
<?php
session_start();
require 'tools/config.php';
include 'layout/header-register.php';

$nom = "";
$prenom = "";
$cin = "";
$email = "";
$password = "";
$phone = "";
$address = "";
$role = "citoyen"; // Valeur par défaut

$nom_error = "";
$prenom_error = "";
$cin_error = "";
$email_error = "";
$password_error = "";
$phone_error = "";
$address_error = "";

$error = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $cin = $_POST['cin'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $role = $_POST['role']; // Récupération du rôle

    // Validation du nom
    if (empty($nom)) {
        $nom_error = "Le nom est requis.";
        $error = true;
    }

    // Validation du prénom
    if (empty($prenom)) {
        $prenom_error = "Le prénom est requis.";
        $error = true;
    }

    // Validation du CIN
    if (empty($cin)) {
        $cin_error = "Le CIN est requis.";
        $error = true;
    } else {
        // Vérifier si le CIN est déjà utilisé
        $stmt = $conn->prepare("SELECT id FROM users WHERE cin = ?");
        $stmt->bind_param("s", $cin);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $cin_error = "Ce CIN est déjà utilisé.";
            $error = true;
        }
        $stmt->close();
    }

    // Validation de l'email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "L'adresse email n'est pas valide.";
        $error = true;
    }

    // Validation du mot de passe
    if (strlen($password) < 8) {
        $password_error = "Le mot de passe doit contenir au moins 8 caractères.";
        $error = true;
    }

    // Validation du téléphone
    if (!empty($phone) && !preg_match('/^[0-9]{10}$/', $phone)) {
        $phone_error = "Le numéro de téléphone n'est pas valide.";
        $error = true;
    }

    // Validation de l'adresse
    if (empty($address)) {
        $address_error = "L'adresse est requise.";
        $error = true;
    }

    if (!$error) {
        // Hash du mot de passe
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insertion dans la base de données
        $stmt = $conn->prepare("INSERT INTO users (nom, prenom, cin, email, mot_de_passe, phone, address, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $nom, $prenom, $cin, $email, $hashed_password, $phone, $address, $role);

        if ($stmt->execute()) {
            // Succès de l'inscription, rediriger vers la page correspondante
            $_SESSION["success_message"] = "Inscription réussie !";
            if ($role == 'policier') {
                header("location: policier.php"); // Redirection vers policier.php
            } elseif ($role == 'citoyen') {
                header("location: citoyen.php"); // Redirection vers citoyen.php
            } else {
                header("location: map/map.php"); // Redirection vers map.php ou une autre page par défaut
            }
            exit;
        } else {
            echo "Erreur: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Lien vers le CSS de Bootstrap pour le style -->
</head>

<body>
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-6 mx-auto border shadow p-4">
                <h2 class="text-center mb-4">Inscription</h2>
                <hr />
                <form method="post">
                    <div class="form-group row">
                        <label for="nom" class="col-sm-4 col-form-label">Nom*</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="nom" name="nom"
                                value="<?= htmlspecialchars($nom) ?>">
                            <span class="text-danger"><?= $nom_error ?></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="prenom" class="col-sm-4 col-form-label">Prénom*</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="prenom" name="prenom"
                                value="<?= htmlspecialchars($prenom) ?>">
                            <span class="text-danger"><?= $prenom_error ?></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="cin" class="col-sm-4 col-form-label">CIN*</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="cin" name="cin"
                                value="<?= htmlspecialchars($cin) ?>">
                            <span class="text-danger"><?= $cin_error ?></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-sm-4 col-form-label">Email*</label>
                        <div class="col-sm-8">
                            <input type="email" class="form-control" id="email" name="email"
                                value="<?= htmlspecialchars($email) ?>">
                            <span class="text-danger"><?= $email_error ?></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="phone" class="col-sm-4 col-form-label">Téléphone</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="phone" name="phone"
                                value="<?= htmlspecialchars($phone) ?>">
                            <span class="text-danger"><?= $phone_error ?></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="address" class="col-sm-4 col-form-label">Adresse</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" id="address" name="address"
                                rows="3"><?= htmlspecialchars($address) ?></textarea>
                            <span class="text-danger"><?= $address_error ?></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-sm-4 col-form-label">Mot de passe*</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" id="password" name="password">
                            <span class="text-danger"><?= $password_error ?></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="role" class="col-sm-4 col-form-label">Rôle*</label>
                        <div class="col-sm-8">
                            <select id="role" name="role" class="form-control">
                                <option value="citoyen" <?= ($role == 'citoyen') ? 'selected' : '' ?>>Citoyen</option>
                                <option value="policier" <?= ($role == 'policier') ? 'selected' : '' ?>>Policier</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="offset-sm-4 col-sm-8">
                            <button type="submit" class="btn btn-primary">S'inscrire</button>
                            <a href="/index.php" class="btn btn-outline-primary">Annuler</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Scripts JS de Bootstrap -->
</body>

</html>
