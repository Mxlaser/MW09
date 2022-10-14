<?php

include('model.php');
$donnees="/drone";
//$option=array(CURLOPT_HTTPGET=>true);
$reponseAPITab=executerRequeteCurl($donnees,"GET");
//$reponseAPITab=json_decode($reponseAPIJson,true);


echo "<div ><table class='tableau_statistique '><tr class='centrer'><th>Numéro drône</th><th>Marque</th><th>Modèle</th><th>Référence</th><th>Date achat</th><th>Action</th></tr>";

for($i=0;$i<count($reponseAPITab);$i++){
  echo "<form action='' method='post'><tr class='centrer'>";
  $donneesDrone=$reponseAPITab[$i];
  foreach ($donneesDrone as $keyUnVol => $valueUnVol) {
    if($keyUnVol=='iddrone')
      echo "<td><input type='number' name='$keyUnVol' value='$valueUnVol' readonly></td>";
    else
      echo "<td><input type='text' class='tableau_statistique_input' name='$keyUnVol' value='$valueUnVol'></td>";
  }
  echo "<td><input type='submit' class='tableau_statistique_input'name='MiseAJourDrone' value='Mettre à jour'></td>";
  echo "</tr></form>";
}
echo "</table></div>";

 ?>
