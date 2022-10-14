<?php

include('model.php');
$donnees="/utilisateur";
//$option=array(CURLOPT_HTTPGET=>true);
$reponseAPITab=executerRequeteCurl($donnees,"GET");
//$reponseAPITab=json_decode($reponseAPIJson,true);


echo "<div ><table class='tableau_statistique '><tr class='centrer'><th>Numéro utilisateur</th>
   <th>Nom</th><th>Prénom</th> <th>Email</th><th>Datede naissancec</th><th>Pseudo</th></tr>";

for($i=0;$i<count($reponseAPITab);$i++){
  echo "<form action='rest.php' method='post'><tr class='centrer'>";
  $donneesDrone=$reponseAPITab[$i];
  foreach ($donneesDrone as $keyUnVol => $valueUnVol) {
    if($keyUnVol=='idutilisateur')
      echo "<td><input type='number' name='$keyUnVol' value='$valueUnVol' readonly></td>";
    else
      echo "<td><input type='text' class='tableau_statistique_input' name='$keyUnVol' value='$valueUnVol'></td>";
  }
  echo "<td><input type='submit' class='tableau_statistique_input'name='MiseAJourVol' value='Mettre à jour'></td>";
  echo "</tr></form>";
}
echo "</table></div>";



?>
