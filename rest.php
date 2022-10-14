<?php
  //$id=new PDO('mysql:host=localhost;dbname=MW04_drone_nikola;charset=utf8','nikola','snirlla');

  require_once "model.php";
  $id = ConnectDB(); //---CONNEXION A LA BDD

  session_start(); //Création d'une session (suite en bas)
//---Connexion---
  if(!empty($_POST))
  {
  	if(isset($_POST['valider']))
  	{
  		$pseudo = $_POST['pseudo_Utilisateur'];
  		$mdp = $_POST['mot_De_Passe_Utilisateur'];

      $reqValider = "select nom,prenom from utilisateur where pseudo=? and mdp=?";
      $tableauDeDonnees = array($pseudo, $mdp);
      $resValider = executerRequete($id, $reqValider, $tableauDeDonnees);

  		if(empty($resValider))
  			header('Location:formulaire_connexion.php?erreur=pseudo_Utilisateur');
  		else
  		{
  			setcookie("pseudo", $pseudo, "time()+3600","http://172.20.21.208/~nikola/MW09/index.php");
  			header('Location:formulaire_connexion.php');
  		}
  	}
  }

//---Inscription---
  if(!empty($_POST))
  {
    if(isset($_POST['inscription']))
    {
      foreach ($_POST as $cle => $valeur)
      {
        $$cle = $valeur;
      }
      $reqCheckNom = "select nom from utilisateur where pseudo=?";
      $tableauDeDonnees = array($pseudo_Utilisateur);
      $resCheckNom = executerRequete($id, $reqCheckNom, $tableauDeDonnees);

      if(empty($resCheckNom))
      {
        $reqInscription = "insert into utilisateur (nom, prenom, email, naissance, pseudo, mdp) values(?,?,?,?,?,?)";
        $tableauDeDonnees = array($pseudo_Utilisateur);
        $resInscription = executerRequete($id, $reqInscription, $tableauDeDonnees);

        setcookie("pseudo", $pseudo_Utilisateur, "time()+3600","http://172.20.21.208/~nikola/MW09/index.php");
        header('Location:formulaire_inscription.php');
      }
      else
      {
        header('Location:formulaire_inscription.php?erreur=pseudo_Utilisateur');
      }
    }
  }
//---Deconnexion---
  if(isset($_GET['deconnexion']))
  {
      setcookie("pseudo", '', "time()-1","http://172.20.21.208/~nikola/MW09/index.php");
      header('Location:index.php');
  }

//---Profil---
  if(isset($_GET['profil']))
  {
    $reqProfil = "select nom,prenom,email,pseudo from utilisateur where pseudo=?";
    $tableauDeDonnees = array($pseudo_Utilisateur);
    $resProfil = executerRequete($id, $reqProfil, $tableauDeDonnees);
    $nom=$resProfil[0]["nom"];
    $prenom=$resProfil[0]["prenom"];
    $email=$resProfil[0]["email"];
    $pseudo=$pseudo[0]["pseudo"];

    header('Location:formulaire_profil.php?nom='.$nom.'&prenom='.$prenom.'&email='.$email.'&pseudo='.$_COOKIE['pseudo']);
  }

//---Mise à Jour du Profil---
  if(isset($_POST['miseAJour']))
  {
    foreach ($_POST as $cle => $valeur)
    {
      $$cle = $valeur;
    }
    $reqCheckMAJ = "select idutilisateur from utilisateur where pseudo=?";
    $tableauDeDonnees = array($pseudo_Utilisateur);
    $resCheckMaj = executerRequete($id, $reqCheckMAJ, $tableauDeDonnees);

    //---Verif si le champ du pseudo est changé---
    if($pseudo_Utilisateur == $_COOKIE['pseudo']) //si pas changé
    {
      $reqMAJ = "update utilisateur set nom=?, prenom=?, email=? where pseudo=?";
      $tableauDeDonnees = array($pseudo_Utilisateur);
      $resMaj = executerRequete($id, $reqMAJ, $tableauDeDonnees);

      setcookie("pseudo", $pseudo_Utilisateur, "time()-1","http://172.20.21.208/~nikola/MW09/index.php");
      setcookie("pseudo", $pseudo_Utilisateur, "time()+3600","http://172.20.21.208/~nikola/MW09/index.php");
      header('Location:index.php');

    }

    else //si changé
    {
      $reqCheckNom = "select nom from utilisateur where pseudo=?";
      $tableauDeDonnees = array($pseudo_Utilisateur);
      $resCheckNom = executerRequete($id, $reqCheckNom, $tableauDeDonnees);

      //---Verif si pseudo est déjà utilisé---
      if(!empty($resCheckNom)) //pseudo dispo
      {
        $reqMajFull = "update utilisateur set nom=?, prenom=?, email=?, pseudo=? where idutilisateur=?";
        $tableauDeDonnees = array($pseudo_Utilisateur);
        $resMAJFull = executerRequete($id, $reqMajFull, $tableauDeDonnees);

        setcookie("pseudo", $pseudo_Utilisateur, "time()-1","http://172.20.21.208/~nikola/MW09/index.php");
        setcookie("pseudo", $pseudo_Utilisateur, "time()+3600","http://172.20.21.208/~nikola/MW09/index.php");
        header('Location:index.php');
      }
      else //pseudo non dispo
      {
        header('Location:formulaire_profil.php?erreur&nom='.$nom.'&prenom='.$prenom.'&email='.$email.'&pseudo='.$pseudo_Utilisateur);
      }
    }
  }

//---Clic sur l'image drone
  //voir aircraftDrone.php

//---Clic sur l'image vol
  //voir flyDrone.php

//---Clic sur l'image utilisateur
  //voir userDrone.php

//---Maj données Drones---
  if(isset($_POST['MiseAJourListeDrones']))
  {
    foreach ($_POST as $cle => $valeur)
    {
      $$cle = $valeur;
    }
    $reqverifListeDrone = "select refdrone from drone where iddrone=?";
    $tableauDeDonnees = array($pseudo_Utilisateur);
    $resverifListeDrone = executerRequete($id, $reqverifListeDrone, $tableauDeDonnees);
    $reference=$resverifListeDrone[0]["refdrone"];

    //---Verif si reference drone changée---
    if($reference == $refdrone) //si pas changée
    {
      $reqMAJListeDrone = "update drone set marque=?, modele=?, dateAchat=? where iddrone=?";
      $tableauDeDonnees = array($pseudo_Utilisateur);
      $resMAJListeDrone = executerRequete($id, $reqMAJListeDrone, $tableauDeDonnees);

      header('Location:suivi.php?listeDrones');
    }
    else //si changée
    {
      $reqrefDrone = "select refdrone from drone where refdrone=?";
      $tableauDeDonnees = array($pseudo_Utilisateur);
      $resrefDrone = executerRequete($id, $reqrefDrone, $tableauDeDonnees);

      //---Verif si reference drone est déjà utilisée---
      if(!empty($resrefDrone)) //reference drone dispo
      {
        $reqMAJDrone = "update drone set marque=?, modele=?, refdrone=?, dateAchat=? where iddrone=?";
        $tableauDeDonnees = array($pseudo_Utilisateur);
        $resMAJDrone = executerRequete($id, $reqMAJDrone, $tableauDeDonnees);

        header('Location:suivi.php?listeDrones');
      }
      else //reference drone non dispo
      {
        header('Location:suivi.php?erreur&suivi'); //ne fonctionne pas
      }
    }
  }

//---Maj données Vols---
  if(isset($_POST['MiseAJourListeVols']))
  {
    foreach ($_POST as $cle => $valeur)
    {
      $$cle = $valeur;
    }
    $reqMAJListeVol = "update drone set datevol=? where idvol=?";
    $tableauDeDonnees = array($pseudo_Utilisateur);
    $resMAJListeVol = executerRequete($id, $reqMAJListeVol, $tableauDeDonnees);

    header('Location:suivi.php?listeVols');
  }

//---Maj données Vols---
  //Pas necessaire car fait dans l'onglet profil

//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

//---SELECTIONNER TT LES USERS
    /*$req = "select * from utilisateur";
    $tableauDeDonnees = array();
    $res = executerRequete($id,$req,$tableauDeDonnees);
    print_r($res);*/

//---RECUP LES INFOS DE LA REQUETE
    $req_type = $_SERVER["REQUEST_METHOD"];
    $req_path = $_SERVER["PATH_INFO"];
    $req_data = explode("/", $req_path);

//---GET
    if($req_type == "GET")
    {
    //---SI LA REQUETE CONTIENT SUIVI
      if($req_data[1] == "suivi")//-------NEW
      {

        $reqDrone = "select count(iddrone) as nbDrone from drone";
        $resDrone = executerRequete($id,$reqDrone,array());
        $nbDrone = $resDrone[0]['nbDrone'];

        $reqVol = "select count(idvol) as nbVol from vol";
        $resVol = executerRequete($id,$reqVol,array());
        $nbVol = $resVol[0]['nbVol'];

        $reqUtilisateur = "select count(idutilisateur) as nbUtilisateur from utilisateur";
        $resUtilisateur = executerRequete($id,$reqUtilisateur,array());
        $nbUtilisateur = $resUtilisateur[0]['nbUtilisateur'];

        $resultatsEnTableau = array("nbDrone" => $nbDrone, "nbVol" => $nbVol, "nbUtilisateur" => $nbUtilisateur);
        $resultatsEnJson = json_encode($resultatsEnTableau);
        echo $resultatsEnJson;
      }
    //---SI LA REQUETE CONTIENS DRONE
      if($req_data[1] == "drone")//-------MW09
      {
        $reqlisteDrone = "select * from drone";
        $reslisteDrone = executerRequete($id,$reqlisteDrone,array());

        $resultatsEnJson = json_encode($reslisteDrone);
        echo $resultatsEnJson;
      }
    //---SI LA REQUETE CONTIENS VOL
      if($req_data[1] == "vol")//-------MW09
      {
        $reqlisteVol = "select idvol, datevol, iddrone, nom from vol inner join utilisateur on vol.idutilisateur = utilisateur.idutilisateur";
        $reslisteVol = executerRequete($id, $reqlisteVol, array());

        $resultatsEnJson = json_encode($reslisteVol);
        echo $resultatsEnJson;
      }
    //---SI LA REQUETE CONTIENS UTILISATEUR
      if($req_data[1] == "utilisateur")//-------MW09
      {
        $reqlisteUtilisateurs = "select * from utilisateur";
        $reslisteUtilisateurs = executerRequete($id, $reqlisteUtilisateurs, array());

        $resultatsEnJson = json_encode($reslisteUtilisateurs);
        echo $resultatsEnJson;
      }
    //---SI LA REQUETE CONTIENS ETAT
      if($req_data[1] == "etat")//-------MW09
      {
        //$reqetat = "select idetat, idvol, pitch, roll, yaw, vgx, vgy, vgz, templ, temph, tof, h, bat, baro, time, agx, agy, agz from etat";
        $reqetat = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'etat' ORDER BY ORDINAL_POSITION";
        $resetat = executerRequete($id, $reqetat, array());

        $resultatsEnJson = json_encode($resetat);
        echo $resultatsEnJson;
      }
      //---SI LA REQUETE CONTIENT GRAPH_TEST
      if($req_data[1] == "graph_test")//-------MW09
      {
        $reqgraph = "select idetat, ".$req_data[3]." from etat where idvol = ?";
        $resgraph = executerRequete($id, $reqgraph, array($req_data[2]));
        $resultatsEnJson = json_encode($resgraph);
        echo $resultatsEnJson;
      }
    }

//---POST
    else if($req_type == "POST")
    {
      $donneesVolJSON = file_get_contents("php://input"); //---RECUP LE CONTENU DU FICHIER JSON ENVOYÉ DANS LA REQUETE
      $donneesVolAssoc = json_decode($donneesVolJSON, true);

    //---SI LA REQUETE CONTIENS VOLS
      if($req_data[1] == "vols")
      {
        $nomVol = $donneesVolAssoc["donneesVol"]["nom"];
        $time = $donneesVolAssoc["donneesVol"]["time"];
        $dateVol = date('Y-m-d H-i-s', $time);
        $numeroDrone = $donneesVolAssoc["donneesVol"]["numero"];
        $etatsVol = $donneesVolAssoc["donneesVol"]["etats"];

      //---SELECTIONNER L'ID UTILISATEUR EN CONNAISSANT LE NOM
        $reqUtilisateur = "select idutilisateur from utilisateur where nom = ?";
        $tableauDeDonnees = array($nomVol);
        $resUtilisateur = executerRequete($id,$reqUtilisateur,$tableauDeDonnees);

      //---SELECTIONNER L'ID DRONE EN CONNAISSANT LA REF DU DRONE
        $reqDrone = "select iddrone from drone where refdrone = ?";
        $tableauDeDonnees = array($numeroDrone);
        $resDrone = executerRequete($id,$reqDrone,$tableauDeDonnees);

      //---SI LE DRONE EXISTE
        if(!empty($resDrone))
        {
          $idDrone = $resDrone["0"]["iddrone"];
        //setcookie("iddrone", $idDrone, "time()+3600","http://172.20.21.208/~nikola/MW08/web/rest.php");
        }

      //---SI LE DRONE N'EXISTE PAS
        if(!isset($idDrone))
        {
          $reqDrone = "insert into drone (refdrone) values (?)";
          $tableauDeDonnees = array($numeroDrone);
          $resDrone = executerRequete($id, $reqDrone, $tableauDeDonnees);
        //$idCookie = recupererLeDernierIdInserer($id);
        //setcookie("iddrone", $idCookie, "time()+3600","http://172.20.21.208/~nikola/MW08/web/rest.php");
        }

      //---SI L'UTILISATEUR EXISTE
        if(!empty($resUtilisateur))
        {
          $idUtilisateur = $resUtilisateur["0"]["idutilisateur"];
        //setcookie("idutilisateur", $idUtilisateur, "time()+3600","http://172.20.21.208/~nikola/MW08/web/rest.php");

        //---SELECTIONNER L'ID VOL EN CONNAISSANT L'ID UTILISATEUR, LA DATE ET L'ID DU DRONE
          $reqVol = "select idvol from vol where idutilisateur = ? and datevol = ? and iddrone = ?";
          $tableauDeDonnees = array($idUtilisateur, $dateVol, $idDrone);
          $resVol = executerRequete($id,$reqVol,$tableauDeDonnees);

        //---SI LE VOL EXISTE
          if(!empty($resVol))
          {
            $idVol = $resVol["0"]["idvol"];
          //setcookie("idvol", $idVol, "time()+3600","http://172.20.21.208/~nikola/MW08/web/rest.php");

            for($i = 0; $i < count($etatsVol); $i++)
            {
              foreach ($donneesVolAssoc["donneesVol"]["etats"][$i] as $cle => $valeur)
              {
                $$cle = $valeur;
              }
            }
          //---INSERER L'ETAT DE VOL DANS ETAT DRONE
            $reqEtat = "insert into etat (idvol, pitch, roll, yaw, vgx, vgy, vgz, templ, temph, tof, h, bat, baro, time, agx, agy, agz) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $tableauDeDonnees = array($idVol, $pitch, $roll, $yaw, $vgx, $vgy, $vgz, $templ, $temph, $tof, $h, $bat, $baro, $time, $agx, $agy, $agz);
            $resEtat = executerRequete($id,$reqEtat,$tableauDeDonnees);

          }

        //---SI LE VOL N'EXISTE PAS
          if(!isset($idVol))
          {
            $reqVol = "insert into vol (idutilisateur, datevol, iddrone) values (?,?,?)";
            $tableauDeDonnees = array($idUtilisateur, $dateVol, $idDrone);
            $resVol = executerRequete($id, $reqVol, $tableauDeDonnees);
          //$idCookie = recupererLeDernierIdInserer($id);
          //setcookie("idvol", $idCookie, "time()+3600","http://172.20.21.208/~nikola/MW08/web/rest.php");
          }
        }

      //---SI L'UTILISATEUR N'EXISTE PAS
        if(!isset($idUtilisateur))
        {
          $reqUtilisateur = "insert into utilisateur (nom) values (?)";
          $tableauDeDonnees = array($nomVol);
          $resUtilisateur = executerRequete($id, $reqUtilisateur, $tableauDeDonnees);
        //$idCookie = recupererLeDernierIdInserer($id);
        //setcookie("idutilisateur", $idCookie, "time()+3600","http://172.20.21.208/~nikola/MW08/web/rest.php");
        }

      //---SI LA REQUETE CONTIENS UN NOM D'UTILISATEUR
        if(isset($req_data[2]))
        {
        //---
        }
      }

    //---SI LA REQUETE CONTIENS UTILISATEUR
      if($req_data[1] == "utilisateurs")
      {
      //---SI LA REQUETE CONTIENS UN NOM D'UTILISATEUR
        if(isset($req_data[2]))
        {
        //---
        }
      }
    }

?>
