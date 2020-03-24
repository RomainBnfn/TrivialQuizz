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

   // On regarde si l'id passé en méthode get est correct
   if(empty($_GET['id']) || !is_numeric($_GET['id']) )
   {
     exit();
   }

   $id = $_GET['id'];
   // Le quizz n'existe pas !
   if(!existQuizz($bdd, $id))
   {
     exit();
   }

   //Tout est ok : Suppression !
   $bdd -> query("DELETE FROM QUIZ WHERE qui_id = $id");

   echo getNbQuizz($bdd);
?>
