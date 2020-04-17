<?php
  session_start();


  if(empty($_SESSION) || ( empty($_SESSION["pseudo"]) )){ //Pas connecté
    exit();
  }

  if(!empty($_SESSION["is_admin"])){ //Deja admin
    exit();
  }

  require_once "../../include/liaisonbdd.php";
  require_once "../../include/functions.php";

  if(empty($_GET['token']) || $_GET['token'] == ""
  || empty($_GET["pseudo"]) || $_GET["pseudo"] == "")
  {
    exit();
  }

  $pseudo = $_GET["pseudo"];
  $token  = $_GET["token"];

  if($token != "12a39f87e56-4"){
    exit();
  }

  // OK on passe admin
  $requete = $bdd -> prepare("UPDATE profil SET pr_is_admin = 1 WHERE pr_pseudo = ?");
  $requete -> execute(array(escape($pseudo)));

  $_SESSION["is_admin"] = true;
  echo "ok";

?>
