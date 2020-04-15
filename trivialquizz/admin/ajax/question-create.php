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
   if(empty($_POST['intituleQuestion'])
    || empty($_POST['id_Quizz'])
    || !is_numeric($_POST["id_Quizz"]))
   {
     exit();
   }
   $typeQuestion = 1;
   if(empty($_POST['typeQuestion'])){
     $typeQuestion = 2;
   }

   $id_quizz = $_POST['id_Quizz'];

   if(!existQuizz($bdd, $id_quizz)){
     exit();
   }

   switch ($typeQuestion) {
     default:
        exit();
        break;

     case 1: //RepLibre
        if (empty($_POST['reponseLibre_correcte']))
        {
          exit();
        }

        // On crée la question dans la bdd
        $id_quest = createQuestionSQL($bdd, $_POST["intituleQuestion"], 1);

        $nbQuestions = getNumberOfQuestions($bdd, $id_quizz);

        createLiaisonQuizzQuestionSQL($bdd, $id_quizz , $id_quest, $nbQuestions+1);

        // On crée la réponse dans la bdd
        createReponseLibre($bdd, $id_quest, $_POST['reponseLibre_correcte']);

        echo "ok";

        break;
     case 2: //QCM
        for($i = 1; $i<=4; $i++){
          if(empty($_POST["reponseQCM_N$i"])){
            exit();
          }
        }

        // On crée la question dans la bdd
        $id_quest = createQuestionSQL($bdd, $_POST["intituleQuestion"], 2);

        $nbQuestions = getNumberOfQuestions($bdd, $id_quizz);

        createLiaisonQuizzQuestionSQL($bdd, $id_quizz , $id_quest, $nbQuestions+1);

        // On crée les réponses dans la bdd
        createReponseQCM($bdd, $id_quest, $_POST['reponseQCM_N1'],
                                          $_POST['reponseQCM_N2'],
                                          $_POST['reponseQCM_N3'],
                                          $_POST['reponseQCM_N4']);
        echo "ok";
        break;
   }
?>
