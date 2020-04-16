<?php
  /*  Ceci n'est pas une page.
   */
   session_start();

   // On regarde si l'utilisateur est bien un admin
   if(!isset($_SESSION['is_admin']))
   {
     exit();
   }

   require_once "../../include/liaisonbdd.php";
   require_once "../../include/functions.php";

   if(empty($_POST) || empty($_POST["nom"])
     || empty($_POST["desc"])
     || empty($_POST["theme"])
     || !is_numeric($_POST["theme"]))
    {
      exit();
    }

    $id_theme = $_POST["theme"];

    if(!existTheme($bdd, $id_theme)) //On passe le post direct : C'est un number
    {
      exit();
    }

    // Le nom existe déjà
    $names = getAllQuizzNames($bdd);
    if(!empty($names) && in_array($_POST["nom"], $names))
    {
      exit();
    }

   // On crée le quizz dans la bdd
   $requete = $bdd -> prepare("INSERT INTO quiz (qui_desc,
                                                 qui_temps,
                                                 qui_malus,
                                                 th_id,
                                                 qui_nom)
                                                 VALUES ( ? , 300, 0 , ? , ?)");
   $result = $requete -> execute(array(
                             escape($_POST["desc"]),
                             escape($_POST["theme"]),
                             escape($_POST["nom"])));
   echo $bdd->lastInsertId();
?>
