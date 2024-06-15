<?php
session_start(); // Démarre une nouvelle session
if (!isset($_SESSION['user_id'])) {  // Vérifie si la variable de session 'user_id' existe.
    // Si elle n'existe pas, cela signifie que l'utilisateur n'est pas connecté.
    header('Location: login.php');
    exit();
}

$role = $_SESSION['role']; // Récupère le rôle de l'utilisateur depuis la session.
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Map Drawing App</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
    <link rel="stylesheet" href="style.css">
</head>

<body>
<header>
    <img src="/images/logo.png" alt="Logo">
    <span class="logo">Crime Mapper</span>
    <nav>
        <a href="/index.php" class="active">Home</a>
        <a href="/about.php">About</a>
    </nav>
    <div class="auth-buttons">
        <a href="/logout.php">Logout</a>
    </div>
</header>

<div class="container">
    <div id="map" class="map-container"></div>
</div>

<?php if ($role == 'citoyen'): ?>
    <div class="container">
        <button id="saveDrawing">Enregistrer le dessin</button>
    </div>
<?php endif; ?>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script><!-- Inclut la bibliothèque Leaflet -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>  <!-- Inclut la bibliothèque jQuery -->
<script>
    // Assurez-vous que la console affiche un message pour le rôle
    console.log('User role:', '<?php echo $role; ?>');
    
    // Assurez-vous que la carte est bien initialisée
    console.log('Initializing map...');
    
    var map = L.map('map').setView([33, -7], 13);

    // Ajoute les tuiles de carte OpenStreetMap à la carte
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(map);

    var drawnItems = new L.FeatureGroup(); // Crée un groupe d'éléments dessinés
    map.addLayer(drawnItems); // Ajoute le groupe à la carte

    <?php if ($role == 'citoyen'): ?>
        // Configuration pour les citoyens
        console.log('Setting up drawing tools for citizen...');
        var drawControl = new L.Control.Draw({ // nouvelle instance qui est un contrôleur de dessin pour Leaflet. 
            edit: {
                featureGroup: drawnItems // Le groupe de couches où seront stockées les formes dessinées
            },
            // Configuration pour le dessin des formes
            draw: {
                polygon: true, // Permet le dessin de polygones
                polyline: false,
                rectangle: false,
                circle: false,
                marker: false,
                circlemarker: false
            }
        });
        // Ajout du contrôleur de dessin à la carte
        map.addControl(drawControl);

        map.on(L.Draw.Event.CREATED, function (e) {
            console.log('Drawing created');
            var layer = e.layer; // Récupération de la couche dessinée
            drawnItems.addLayer(layer); // Ajout des formes dessinées
        });

        // Gestionnaire de clic pour enregistrer le dessin
        $('#saveDrawing').on('click', function () {
            var drawingData = JSON.stringify(drawnItems.toGeoJSON()); // Conversion des formes dessinées en format GeoJSON
            console.log('Saving drawing:', drawingData);
            $.post('save_drawing.php', { drawing_data: drawingData }, function (response) {  // Affichage de la réponse du serveur après l'enregistrement
                alert(response);
            });
        });
    <?php else: ?>
        // Configuration pour les policiers
        console.log('Fetching drawings for police officer...');
        $.getJSON('get_drawings.php', function (data) {
            console.log('Fetched data:', data); // Debug log
            if (data && data.length > 0) {
                data.forEach(function (drawing) {
                    // Création d'une couche GeoJSON avec le style rouge
                    var geoJsonLayer = L.geoJSON(drawing, {
                        style: function () {
                            return { color: 'red' };
                        }
                    }).addTo(map); // Ajout de la couche à la carte
                    drawnItems.addLayer(geoJsonLayer);
                });
            } else {
                console.log('No drawings to display.');
            }
        }).fail(function (jqxhr, textStatus, error) {
            console.error('Error fetching drawings:', textStatus, error); // Error log
        });
    <?php endif; ?>

    // Message de confirmation que la carte a été initialisée
    console.log('Map initialization complete.');
</script>
</body>

</html>
