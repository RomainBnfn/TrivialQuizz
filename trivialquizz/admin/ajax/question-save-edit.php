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


  if(empty($_POST['id']) || !is_numeric($_POST['id'])
    || empty($_POST['libelle']) || $_POST['libelle'] == "")
  {
    exit();
  }

  $id = $_POST['id'];

  //Tout est ok : Modifications !
  $requete = $bdd -> prepare("UPDATE question SET que_lib = ?
                                  WHERE que_id = $id;");

  $requete -> execute(array(escape($_POST['libelle'])));
  echo "ok";
?>