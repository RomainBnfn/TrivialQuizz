<?php
  function aQuestionHasBeenRemoved($idQuest, $idQuizz, $order, $nbQuestion, $nbOccurence)
  {
    $_THEME;
    $_THEME["id"] = $result["th_id"];
    $_THEME["nom"] = $result["th_nom"];
    $_THEME["desc"] = $result["th_description"];
    $_THEME["couleur"] = $result["th_couleur"];
    $_THEME["is_Principal"] = $result["th_is_principal"];
    return $_THEME;
  }
?>
