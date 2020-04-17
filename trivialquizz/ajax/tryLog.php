<?php
  session_start();

  if(!empty($_SESSION) && ( !empty($_SESSION["pseudo"]) || !empty($_SESSION["is_admin"]) )){
    exit();
  }

  require_once "../include/liaisonbdd.php";
  require_once "../include/functions.php";

  if(empty($_POST['pseudo']) || $_POST['pseudo'] == ""
    || empty($_POST['password']) || $_POST['password'] == "")
  {
    exit();
  }

  $pseudo = $_POST["pseudo"];
  $password = $_POST["password"];

  $requete = $bdd -> prepare("SELECT * FROM profil WHERE pr_pseudo= ? AND pr_password= ?");
  $requete -> execute(array($pseudo,
                            $password));
  $result = $requete -> fetch();

  if(empty($result)){
    echo "fail";
    exit();
  }

  // Ok
  $_SESSION['pseudo'] = escape($pseudo);

  if($result['pr_is_admin'] == 1){
    $_SESSION['is_admin'] = true;
  }
  echo "ok";

?>
