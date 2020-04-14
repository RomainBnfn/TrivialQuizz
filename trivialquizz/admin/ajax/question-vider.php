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
   if(empty($_POST['id_Quizz'])
    || !is_numeric($_POST["id_Quizz"]))
   {
     exit();
   }


   $id_quizz = $_POST['id_Quizz'];

   if(!existQuizz($bdd, $id_quizz)){
     exit();
   }

  //Tout est ok : Suppression !

  //On supprime toute les questions qui ne sont pas liées à d'autre quizzes
  $bdd -> query("DELETE FROM question WHERE que_id IN (SELECT que_id FROM quiz_quest WHERE que_id = $id_question GROUP BY que_id HAVING COUNT(que_id)<= 1)");

  //On délie les questions qui sont liées à d'autre quizzes
  $bdd -> query("DELETE FROM quiz_quest WHERE que_id IN (SELECT que_id FROM quiz_quest WHERE que_id = $id_question GROUP BY que_id HAVING COUNT(que_id)<= 1)");

  echo "ok";
?>
