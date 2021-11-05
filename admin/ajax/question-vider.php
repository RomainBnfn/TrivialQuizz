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

   // On regarde si l'id passé en méthode get est correct
   if(empty($_GET['idQuizz'])
    || !is_numeric($_GET["idQuizz"]))
   {
     exit();
   }


   $idQuizz = $_GET['idQuizz'];

   if(!existQuizz($bdd, $idQuizz)){
     exit();
   }

  //Tout est ok : Suppression !

  //On supprime toute les questions qui ne sont pas liées à d'autre quizzes
  $bdd -> query("DELETE FROM question WHERE que_id IN (SELECT que_id FROM quiz_quest WHERE que_id = $idQuizz GROUP BY que_id HAVING COUNT(que_id)<= 1)");

  //On délie les questions qui sont liées à d'autre quizzes
  $bdd -> query("DELETE FROM quiz_quest WHERE que_id IN (SELECT que_id FROM quiz_quest WHERE que_id = $idQuizz GROUP BY que_id HAVING COUNT(que_id)<= 1)");

  echo "ok";
?>
