<?php
  include('model.php');
  $donnees = "/etat";
  $reponseAPITab = executerRequeteCurl($donnees, "GET");

//---1ERE DIV
  $form = "<div id = 'menu_graphe' class = 'menu_graphe'>";
  for ($i = 1; $i < count($reponseAPITab); $i++)
  {
    $form .= "<form action = 'index.php' method = 'get' class = 'formEtat'>";
    $etatsVol = $reponseAPITab[$i];
    foreach ($etatsVol as $cle => $valeur)
    {
      if($valeur == 'idvol')
      {
        $form .= "<label class = 'etat' for = '$valeur'><input type = 'hidden' name = '$valeur' value = '".$_GET['idvol']."' class = 'etat2' id = '$valeur'></label>";
      }
      else
      {
        $form .= "<label class = 'etat' for = '$valeur'><input type = 'checkbox' name = 'etat[]' value = '$valeur' class = 'etat2' id = '$valeur'>$valeur</label>";
      }
    }
  }

  $form .= "<label class = 'button' for = 'button'><input type = 'button' name = 'AfficherGrapheVol' value = 'Afficher graphe' id = 'button' onclick = 'AfficherGraphe()'></label>";//type = 'submit' pour l url
  $form .= "</form></div>";
  echo $form;

//---2E DIV
  echo "<div id = 'container_graphe' class = 'container_graphe'>
  </div>";
?>

<script src="//cdn.amcharts.com/lib/4/core.js"></script>
<script src="//cdn.amcharts.com/lib/4/charts.js"></script>
<script src = "JS/graphe.js"></script>
