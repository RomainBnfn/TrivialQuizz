<?php

  function aQuestionHasBeenRemoved($idQuest, $idQuizz, $order, $nbQuestion, $nbOccurence){
    $_THEME;
    $_THEME["id"] = $result["th_id"];
    $_THEME["nom"] = $result["th_nom"];
    $_THEME["desc"] = $result["th_description"];
    $_THEME["couleur"] = $result["th_couleur"];
    $_THEME["is_Principal"] = $result["th_is_principal"];
    return $_THEME;
  }

  function deleteReponses($bdd, $idQuestion){
    $bdd -> query("DELETE FROM reponse WHERE que_id = $idQuestion");
  }

  function createQuestionSQL($bdd, $intitule, $type){
    $requete = $bdd -> prepare("INSERT INTO QUESTION (que_lib,
                                                      que_type)
                                                    VALUES ( ? , $type)");
    $requete -> execute(array(escape($intitule)));
    return $bdd->lastInsertId();
  }

  function getNumberOfQuestions($bdd, $id_quizz){
    $requete = $bdd -> query("SELECT MAX(qq_order) FROM quiz_quest WHERE qui_id = $id_quizz");
    $result = $requete -> fetch();
    return $result[0];
  }

  function createLiaisonQuizzQuestionSQL($bdd, $id_quizz , $id_quest, $order){
    $bdd -> query("INSERT INTO quiz_quest (qui_id,
                                                que_id,
                                                qq_order)
                                              VALUES ( $id_quizz , $id_quest , $order)");
  }

  function createReponseLibre($bdd, $id_quest, $lib){
    $requete = $bdd -> prepare("INSERT INTO reponse (re_lib,
                                                      re_isBonne,
                                                      que_id)
                                                    VALUES (? , true, $id_quest)");
    $requete -> execute(array(escape($lib)));
  }

  function createReponseQCM($bdd, $id_quest, $lib1, $lib2, $lib3, $lib4){
    $requete = $bdd -> prepare("INSERT INTO reponse (re_lib,
                                                      re_isBonne,
                                                      que_id)
                                                    VALUES (? , true, $id_quest),
                                                           (? , false, $id_quest),
                                                           (? , false, $id_quest),
                                                           (? , false, $id_quest)");

    $requete -> execute(array(escape($lib1),
                              escape($lib2),
                              escape($lib3),
                              escape($lib4)));
  }

  function editReponseLibre($bdd, $idRep, $lib){
    $requete = $bdd -> prepare("UPDATE reponse SET re_lib = ?
                                    WHERE re_id = $idRep;");

    $requete -> execute(array(escape($lib)));

  }

  function editReponseQCM($bdd, $idRe1, $lib1,
                                $idRe2, $lib2,
                                $idRe3, $lib3,
                                $idRe4, $lib4){

    $requete = $bdd -> prepare("UPDATE reponse SET re_lib = ? WHERE re_id = $idRe1;
                                UPDATE reponse SET re_lib = ? WHERE re_id = $idRe2;
                                UPDATE reponse SET re_lib = ? WHERE re_id = $idRe3;
                                UPDATE reponse SET re_lib = ? WHERE re_id = $idRe4;");

    $requete -> execute(array(escape($lib1),
                              escape($lib2),
                              escape($lib3),
                              escape($lib4)));
  }

  function editQuestionLib($bdd, $idQue, $typeQuestion, $lib){
    $requete = $bdd -> prepare("UPDATE question SET que_lib = ?,
                                                    que_type = $typeQuestion
                                    WHERE que_id = $idQue;");

    $requete -> execute(array(escape($lib)));
  }

?>
