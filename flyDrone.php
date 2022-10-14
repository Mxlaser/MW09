<?php

include('model.php');
$donnees="/vol";
$reponseAPITab=executerRequeteCurl($donnees,"GET");

$table="<div ><table class='tableau_statistique '><tr class='centrer'><th>Numéro vol</th>
   <th>Date de vol</th><th>Numéro drône</th> <th>Nom utilisateur</th></tr>";

for($i=0;$i<count($reponseAPITab);$i++){
  $table.="<form action='' method='get'><tr class='centrer'>";
  $donneesDrone=$reponseAPITab[$i];
  foreach ($donneesDrone as $keyUnVol => $valueUnVol) {
    if($keyUnVol=='iddrone' || $keyUnVol=='idvol')
      $table.="<td><input type='number' name='$keyUnVol' value='$valueUnVol' readonly></td>";
    else
      $table.="<td><input type='text' class='tableau_statistique_input' name='$keyUnVol' value='$valueUnVol'></td>";
  }
  $table.="<td><input type='submit' class='tableau_statistique_input'name='MiseAJourVol' value='Mettre à jour'></td>
          <td> <input type='submit' class='tableau_statistique_input'name='AfficherGrapheVol' value='Afficher Vol'></td>";
  $table.="</tr></form>";
}
$table.="</table></div>";

echo $table;

?>
