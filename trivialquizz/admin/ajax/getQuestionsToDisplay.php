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

   if(empty($_GET['idQuizzCible']) || !is_numeric($_GET['idQuizzCible'])
    || empty($_GET['idQuizzSource']) || !is_numeric($_GET['idQuizzSource']))
   {
     exit();
   }
   $idSource = $_GET['idQuizzSource'];
   $idCible = $_GET['idQuizzCible'];

   $requete = $bdd -> query("SELECT question.que_type, question.que_id, question.que_lib FROM question, quiz_quest WHERE question.que_id = quiz_quest.que_id
                                                                            AND qui_id = $idCible
                                                                            AND question.que_id NOT IN (SELECT que_id FROM quiz_quest WHERE qui_id = $idSource) ");

    $results = $requete -> fetchAll();
    if(empty($results) || count($results) == 0){
      echo "<option value='-1' selected disabled>Toutes les questions du quizz ont déjà été importées !</option>";
      exit();
    }

    echo "<option value='-1' selected disabled>Selectionnez la question à importer.</option>";

    $logoLibre = "<i class='fas fa-keyboard' style='color: white !important;'></i>";
    $logoQCM = "<i class='fas fa-list-alt'></i>";

    foreach ($results as $result) {
      $id =  $result["que_id"];
      $lib = $result["que_lib"];
      $type = $result["que_type"];
      if($type==1){
        $logo = "(Libre) ";
      }
      else{
        $logo = "(Qcm) ";
      }
      echo "<option value='$id'>";
      echo $logo;
      echo $lib;
      echo "</option>";
    }
?>
