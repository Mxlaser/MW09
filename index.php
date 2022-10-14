<!DOCTYPE HTML>
<html>
  <head>
    <meta charset="utf-8">
    <title>Mon site de geek</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="CSS\ossature_grille.css">
    <link rel="stylesheet" type="text/css" href="CSS\design.css">
    <link rel="stylesheet" type="text/css" href="CSS\style.css">
  </head>

  <body id ="body" data-theme="light">
    <header>
      <img id="hamburger" src="Images\MyHamburger.png" alt="MyHamburger">
      <h1>Dron'IR</h1>
    </header>

    <nav id="barre_navigation">
      <div>
        <a href="index.php">Présentation</a>
      </div>
      <div>
        <a href="index.php?suivi">Suivi</a>
      </div>
      <?php
        if(isset($_COOKIE['pseudo']))
        {
          echo '<div>
          <a href="formulaire_profil.php">Profil</a>
          </div>';
          echo '<div>
          <a href="rest.php?deconnexion">Deconnexion</a>
          </div>';
        }
        else
        {
          echo '<div>
          <a href="formulaire_connexion.php">Connexion</a>
          </div>';
          echo '<div>
          <a href="formulaire_inscription.php">Inscription</a>
          </div>';
        }
      ?>
    </nav>

    <section>
      <?php

        if(isset($_GET['suivi']))
        {
          include('mainDrone.php');
        }
        else if(isset($_GET['drone']))
        {
          include('aircraftDrone.php');
        }
        else if(isset($_GET['vol']))
        {
          include('flyDrone.php');
        }
        else if(isset($_GET['utilisateur']))
        {
          include('userDrone.php');
        }
        else if(isset($_GET['AfficherGrapheVol']))
        {
          include('graphe.php');
        }

        else
        {
          echo'<h2>Le drone TELLO</h2>
          <div class="deux_colonnes">
            <img id="imgdronetello" src="Images\droneTello.jpg" alt="droneTello">
            <div class="colonne_texte">
              <p>Que vous soyez dans un parc, au bureau ou à la maison, vous pouvez décoller à tout
                moment et découvrir le monde avec un oeil nouveau. Tello est doté de deux antennes
                qui permettent un transmission vidéo parfaitement stable et d’une batterie de
                grande capacité pour des durées de vol impressionnantes.
              </p>
              <div class="quatre_colonnes">
                <p>Temps de vol<br><span class="evidence">13 min</span></p>
                <p>Distance de vol<br><span class="evidence">100 m</span></p>
                <p>Caméra<br><span class="evidence">720P</span></p>
                <p>Transmission<br><span class="evidence">2 antennes</span></p>
              </div>
              <br>
              <div class="videos">
                <video controls>
                  <source src="Videos\DJI RYZE INTEL.mp4" type="video/mp4">
                </video>
                <video controls>
                  <source src="Videos\Introducing Tello - DJI store.mp4" type="video/mp4">
                </video>
              </div>
            </div>
          </div>
          <h2>Le Mavic Air</h2>
          <p>À compléter</p>';
        }
    ?>
    </section>

    <aside>
      <h1>Les drones utilisés</h1>
      <p>Commençons avec le drone <a href="https://www.ryzerobotics.com/fr/tello">Tello</a>. Son prix
        abordable nous permet d'en avoir plusieurs
        et de les utiliser en modules. Son protocole
        de communication est ouvert et disponible.
        Il est alors plus aisé de le piloter à notre
        guise !<br>
        <br>
        Ensuite nous avons le <a href="https://www.dji.com/fr/mavic">Mavic Pro</a>. Ce drone
        sera utilisé en projet, les étudiants
        récupèrent des informations de vol et
        exploitent ensuite les donnée recueuillies
      </p>
      <p id = debutStage>
        Debut du stage dans :
      </p>
      <div id="compteurJours">
        0J
      </div>

      <div id="compteurHeures">
        0H
      </div>

      <div id="compteurMinutes">
        0M
      </div>

      <div id="compteurSecondes">
        0S
      </div>
    </aside>

    <footer>
      <div>
        <h1>Les Drones :</h1>
        <a href="https://www.ryzerobotics.com/fr/tello">-Le drone TELLO</a>
        <a href="https://www.dji.com/fr/mavic">-Le Mavic Pro</a>
      </div>
      <div>
        <h1>Les règles de vol :</h1>
        <a href="https://www.service-public.fr/particuliers/vosdroits/F34630">-Le site officiel du service public</a>
      </div>
      <div>
        <h1>À propos :</h1>
        <a href="CV.html">-Mon CV</a>
        <a href="https://www.w3schools.com/css/css_grid.asp">-Les Grilles CSS</a>
        <button type="button" id="dark_light">Mode sombre</button>
      </div>
    </footer>

    <script src="JS\dark_light.js"></script>
    <script src="JS\navigation.js"></script>
    <script src="JS\compteur.js"></script>
  </body>
</html>
