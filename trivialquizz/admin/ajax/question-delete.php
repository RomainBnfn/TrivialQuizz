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
   if(empty($_GET) || empty($_GET["id"]) || !is_numeric($_GET["id"]))
   {
     exit();
   }

   $id_question = $_GET["id"];

   $bdd -> query("UPDATE quiz_quest as A, quiz_quest as B SET A.qq_order = A.qq_order - 1
                                   WHERE A.qui_id = B.qui_id AND A.qq_order > B.qq_order
                                     AND B.que_id = $id_question");

   $bdd -> query("DELETE FROM question WHERE que_id = $id_question");
   echo "ok";
?>
