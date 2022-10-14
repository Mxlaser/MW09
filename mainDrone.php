<?php
  include('model.php');
  $donnees="/suivi";
  $reponseAPITab=executerRequeteCurl($donnees,"GET");

  echo '<div class="statistique"><a href="?drone">
            <p class="statistique_icone"><img src="./Images/Icones/drone.svg"></p>
            <p class="statistique_valeur">'.$reponseAPITab['nbDrone'].'</p></a></div>';
  echo '<div class="statistique"><a href="?vol">
            <p class="statistique_icone"><img src="./Images/Icones/fly.svg"></p>
            <p class="statistique_valeur">'.$reponseAPITab['nbVol'].'</p></a></div>';
  if(!isset($_COOKIE['pseudo'])){
    echo '<div class="statistique"><a href="?utilisateur">
              <p class="statistique_icone"><img src="./Images/Icones/man.svg"></p>
              <p class="statistique_valeur">'.$reponseAPITab['nbUtilisateur'].'</p></a></div>';
  }
?>
