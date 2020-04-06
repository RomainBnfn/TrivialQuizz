<?php
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
   if(empty($_GET['idQuizz']) || !is_numeric($_GET['idQuizz'])
    || empty($_GET['idQuestion']) || !is_numeric($_GET['idQuestion'])
    || empty($_GET['oldPos']) || !is_numeric($_GET['oldPos'])
    || empty($_GET['direction']) || !is_numeric($_GET['direction']))
   {
     exit();
   }


   $idQuizz = $_GET['idQuizz'];
   $idQuest = $_GET['idQuestion'];
   $oldPos = $_GET['oldPos'];
   $newPos = $_GET['oldPos'] + $_GET['direction'];

   if($newPos <= 0){
     exit();
   }
   
   $requete = $bdd -> query("UPDATE quiz_quest SET qq_order = $oldPos
                                   WHERE qui_id = $idQuizz AND qq_order = $newPos;");

   // On lance direct une requête SQL : que des numbers
   $requete = $bdd -> query("UPDATE quiz_quest SET qq_order = $newPos
                                   WHERE qui_id = $idQuizz AND que_id = $idQuest;");
   echo "ok";
?>
