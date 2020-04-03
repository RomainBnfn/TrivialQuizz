<?php
  /*  Ceci n'est pas une page.
   */
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

   if(empty($_POST)
     || empty($_POST["nom"])
     || empty($_POST["desc"])
     || empty($_POST["couleur"]))
    {
      exit();
    }

    // Le nom existe déjà
    $names = getAllThemesNames($bdd);
    if(!empty($names) && in_array($_POST["nom"], $names))
    {
      exit();
    }

    // On crée le quizz dans la bdd
    $requete = $bdd -> prepare("INSERT INTO theme (th_nom,
                                                  th_couleur,
                                                  th_is_principal,
                                                  th_description)
                                                  VALUES ( ? , ? , 0 , ? )");
    $requete -> execute(array(escape($_POST["nom"]),
                              escape($_POST["couleur"]),
                              escape($_POST["desc"])));

   echo $bdd->lastInsertId();
?>
