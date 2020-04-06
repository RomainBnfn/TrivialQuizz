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
   if(empty($_POST['typeQuestion'])
    || empty($_POST['intituleQuestion'])
    || empty($_POST['id_Quizz'])
    || !is_numeric($_POST["id_Quizz"]))
   {
     exit();
   }


   $id_quizz = $_POST['id_Quizz'];

   if(!existQuizz($bdd, $id_quizz)){
     exit();
   }

   switch ($_POST['typeQuestion']) {
     default:
        exit();
        break;
     case "repLibre":
        if (empty($_POST['reponseLibre_correcte']))
        {
          exit();
        }


        // On crée la question dans la bdd
        $requete = $bdd -> prepare("INSERT INTO QUESTION (que_lib,
                                                          que_type)
                                                        VALUES ( ? , 1)");
        $requete -> execute(array(escape($_POST['intituleQuestion'])));
        $id_quest = $bdd->lastInsertId();

        $requete = $bdd -> query("SELECT MAX(qq_order) FROM quiz_quest WHERE qui_id = $id_quizz");
        $result = $requete -> fetch();
        $max = $result[0];

        // On crée la réponse dans la bdd
        $requete = $bdd -> prepare("INSERT INTO reponse (re_lib,
           	                                              re_isBonne,
                                                          que_id)
                                                        VALUES (? , true, $id_quest)");
        $requete -> execute(array(escape($_POST['reponseLibre_correcte'])));



        $bdd -> query("INSERT INTO quiz_quest (qui_id,
           	                                        que_id,
                                                    qq_order)
                                                  VALUES ( $id_quizz , $id_quest , $max+1)");
        echo "ok";

        break;
     case "QCM":
        exit();
        break;
   }
?>
