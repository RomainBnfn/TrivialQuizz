<?php
  // Les edits considérés ne sont que ceux présents dans les paramètres généraux.
  //
  session_start();

  //TODO: CHANGER CA
  $_SESSION['is_admin'] = true;

  // On regarde si l'utilisateur est bien un admin
  if(!isset($_SESSION['is_admin']))
  {
    exit();
  }

  require_once "../../include/liaisonbdd.php";
  require_once "../../include/functions.php";
  
  // On regarde si l'id passé en méthode get est correct
  if(empty($_POST['id']) || !is_numeric($_POST['id'])
    || empty($_POST['nom'])
    || empty($_POST['desc'])
    || empty($_POST["couleur"]))
  {
    exit();
  }



  $id = $_POST['id'];

  //Vérifications.
  // Le quizz n'existe pas !
  if(!existTheme($bdd, $id))
  {
    exit();
  }

  // Le nom existe déjà ! (Ou est vide)
  $names = getAllThemesNames($bdd);
  if( ($_POST["ancien_nom"] != $_POST['nom'] && in_array($_POST['nom'], $names))
    || $_POST['nom'] == "")
  {
    exit();
  }

  // La description est vide !
  if(empty($_POST['desc']) || $_POST['desc'] == "")
  {
    exit();
  }

  //Tout est ok : Modifications !
  $requete = $bdd -> prepare("UPDATE THEME SET th_nom = ? ,
                                  th_description = ? ,
                                  th_couleur = ?
                                  WHERE th_id = $id;");

  $requete -> execute(array(escape($_POST['nom']) ,
                      escape($_POST['desc']),
                      escape($_POST['couleur'])));
  echo "ok";
?>
