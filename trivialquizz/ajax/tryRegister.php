<?php
  session_start();

  if(!empty($_SESSION) && ( !empty($_SESSION["pseudo"]) || !empty($_SESSION["is_admin"]) )){
    exit();
  }

  require_once "../include/liaisonbdd.php";
  require_once "../include/functions.php";

  if(empty($_POST['pseudo']) || $_POST['pseudo'] == ""
    || empty($_POST['password']) || $_POST['password'] == ""
    || empty($_POST['password-confirmation']) || $_POST['password-confirmation'] == "")
  {
    exit();
  }


  $pseudo = $_POST['pseudo'];
  $password = $_POST['password'];

  if($password != $_POST['password-confirmation']){
    exit();
  }
  if(strlen($password)<6){
    exit();
  }

  $requete = $bdd -> prepare("SELECT pr_pseudo FROM profil WHERE pr_pseudo = ? ");
  $requete -> execute(array($_POST['pseudo']));
  $result = $requete -> fetch();

  if(!empty($result)){ //Le pseudo est déjà prit (entre temps)
    echo "fail";
    exit();
  }
  // OK on inscrit
  $requete = $bdd -> prepare("INSERT INTO profil (pr_pseudo, pr_password) VALUES ( ? , ? )");
  $requete -> execute(array($_POST['pseudo'],
                            $_POST['password']));

  $_SESSION['pseudo'] = escape($pseudo);
  echo "ok";

?>
