<?php
include 'layout/header-index.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>crime mapper</title>
    <link rel="icon" href="/images/logo1.jpg">
    <style>
        body {
            font-family: Arial, sans-serif;

            background-image: url("images/back.jpeg");
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: scroll;


        }

        .navbar {
            background-color: #333;
            overflow: hidden;
        }

        .navbar a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
        }

        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }

        .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
        }

        .container .left {
            width: 45%;
        }

        .container .right {
            width: 45%;
            text-align: center;
        }

        .container img {
            max-width: 100%;
            height: auto;
        }

        .buttons {
            text-align: center;
            margin-top: 20px;
        }

        .buttons a {
            margin: 0 10px;
            padding: 10px 20px;
            text-decoration: none;
            color: white;
            background-color: #007BFF;
            border-radius: 5px;
        }

        .buttons a:hover {
            background-color: #0056b3;
        }

        .card,
        .card-body {
            background-color: transparent;
            /* Supprimer l'arrière-plan des cartes */
            border: none;
            /* Supprimer les bordures */
        }

        p.card-text,
        h1 {
            color: #007BFF;
            /* Ajuster la couleur du texte pour la lisibilité */
        }
    </style>
</head>

<body>



    <main class="container my-5">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">

                        <p class="card-text">
                        <h1>Bienvenue</h1><span style="color: #FFFFFF;"> sur notre plateforme crime mapper dédiée à
                            renforcer la communication et la
                            collaboration entre les citoyens et les forces de l'ordre.</span>
                        </p>


                        <a href="signup.php" class="btn btn-primary">Get Started</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <img src="/images/logo1.png" alt="Logo de l'entreprise" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include 'layout/footer.php'; ?>
    <script>
        window.addEventListener('scroll', function () {
            var header = document.querySelector('header');
            var main = document.querySelector('main');

            if (window.scrollY > header.offsetHeight) {
                header.classList.add('scrolled');
                main.style.marginTop = header.offsetHeight + 'px';
            } else {
                header.classList.remove('scrolled');
                main.style.marginTop = '0';
            }
        });
    </script>

</body>

</html>