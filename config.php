
<?php
//code PHP qui permet de se connecter à une base de données MySQL


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "map_app";

// crée une connexion à la base de données MySQL en utilisant l'objet mysqli
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifie la connexion
//permet de gérer les erreurs de connexion à la base de données. 

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);  //En cas d'erreur, le script affiche un message d'erreur et quitte l'exécution.
}
?>
