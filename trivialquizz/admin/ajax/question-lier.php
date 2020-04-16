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
   require_once "../include/functions.php";

   // On regarde si l'id passé en méthode get est correct
   if(empty($_GET) || empty($_GET["idQuizz"]) || !is_numeric($_GET["idQuizz"])
                    || empty($_GET["idQuest"]) || !is_numeric($_GET["idQuest"]))
   {
     exit();
   }

   $idQuizz = $_GET["idQuizz"];
   $idQuest = $_GET["idQuest"];

   $nbQuestions = getNumberOfQuestions($bdd, $idQuizz);

   createLiaisonQuizzQuestionSQL($bdd, $idQuizz , $idQuest, $nbQuestions+1);

   echo "ok";
?>
