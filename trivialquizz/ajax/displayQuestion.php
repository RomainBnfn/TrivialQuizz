<?php
  session_start();
  require_once "../include/functions.php";
  require_once "../include/liaisonbdd.php";

  if(isset($_GET['idQuizz']) && isset($_GET['numQuest']) && isset($_GET['difficulty'])){
    if(!empty($_GET['idQuizz'] && !empty($_GET['numQuest']) && !empty($_GET['difficulty']))){
      $questions = tryLoadQuizzQuestion($bdd,$_GET['idQuizz']);
      $numQuest = $_GET['numQuest'];
      if(is_null($questions)) {
        echo "finish";
      }else{
        $question = getNiemeQuestion($numQuest,$questions);
        if(!is_null($question)){
          if($question['type']==2){// qcm
            $nbrOfAnswer = min($_GET['difficulty']+1, 5);
            $answers = $question["reponses"];

            //suppression de réponse fausse s'il y en a trop pour la difficulté choisie
            $keys = array_keys($answers);
            while(count($answers)>$nbrOfAnswer){
              $r = rand(0,count($keys)-1);
              if(isset($answers[$keys[$r]])){
                if(!$answers[$keys[$r]]['isBonne']){
                  unset($answers[$keys[$r]]);
                }
              }
            }


            //construction de la question
            $idBonnerep;
            $html = "
            <div class='quest-container'>
            <h1 id='question'>".$question['lib']."</h1>
            <div id='answer-container'>";
            $i = 0;
            foreach($answers as $answer){
              if($answer['isBonne']){
                $idBonneRep = "2#ans".$i."%";
              }
              $html .= "
              <button id='ans".$i."' class='btn answer' type='button' name='answer".$i."' onclick='check(".'"#ans'.$i.'"'.")'>".$answer['lib']."</button>";
              $i++;
            }
            $html .= "
            </div>
            <div id='valide-container'>
            <button id='validated' class='btn' type='button name='valided' onclick='valideQuestion()'>VALIDER</button>
            </div>
            </div>";
          }else{// répsone libre
            $html ="<h1>Réponse libre</h1>";
          }
          echo $html;
        }else{
          echo "empty";
        }
      }
    }else{
      echo "empty";
    }
  }else{
    echo "not set";
  }

  function getNiemeQuestion($n,$questions){
    foreach ($questions as $q) {
      if($q['order']==$n){
        return $q;
      }
    }
    return null;
  }
?>
