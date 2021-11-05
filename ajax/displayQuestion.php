<?php
  session_start();
  require_once "../include/functions.php";
  require_once "../include/liaisonbdd.php";

  if(isset($_GET['idQuizz']) && isset($_GET['numQuest']) && isset($_GET['difficulty'])){
    if(!empty($_GET['idQuizz'] && !empty($_GET['numQuest']) && !empty($_GET['difficulty']))){
      $questions = tryLoadQuizzQuestion($bdd,$_GET['idQuizz']);
      $numQuest = $_GET['numQuest'];
      if(is_null($questions)) {
        echo "null";
      }else{
        $question = getNiemeQuestion($numQuest,$questions);
        if(!is_null($question)){
          if($question['type']==2){// qcm

            //$nbrOfAnswer = min($_GET['difficulty']+1, 5);
            $difficulty = $_GET['difficulty'];
            if($difficulty == 1) $nbrOfAnswer = 2;
            if($difficulty == 2) $nbrOfAnswer = 3;
            if($difficulty == 3) $nbrOfAnswer = 3;
            if($difficulty == 4) $nbrOfAnswer = 3;
            if($difficulty == 5) $nbrOfAnswer = 4;

            $answers = $question["reponses"];

            // mélange des réponses et garde que le nombre nécessaire pour la difficulté choisis
            $answersRandomized = array();
            $temp = array();
            $i = 1;
            $isRightAnswerInclude = false;
            foreach ($answers as $key  => $ans) {
              if($ans['isBonne']) $isRightAnswerInclude = true;
              do{
                $r = rand(0,$nbrOfAnswer-1);
              } while(in_array($r,$temp));
              array_push($temp,$r);
              $answersRandomized[$r] = $ans;
              unset($answers[$key]);
              if($i++ >= $nbrOfAnswer) break;
            }
            if(!$isRightAnswerInclude){
                foreach ($answers as $ans) {
                    if($ans['isBonne'])
                      $answersRandomized[rand(0,$nbrOfAnswer-1)] = $ans;
                }
            }

            //construction de la question
            $infoQuest;
            $html = "
            <div class='quest-container'>
            <h1 id='question'>".$question['lib']."</h1>
            <div id='answer-container'>";
            for($i=0;$i<count($answersRandomized);$i++){
              if($answersRandomized[$i]['isBonne']){
                $infoQuest = "2#ans".$i."%";
              }
              $html .= "
              <button id='ans".$i."' class='btn answer' type='button' name='answer".$i."' onclick='check(".'"#ans'.$i.'"'.")'>".$answersRandomized[$i]['lib']."</button>";
            }
            $html .= "
            </div>
            <div id='valide-container'>
            <button id='validated' class='btn' type='button name='valided' onclick='valideQuestion()'>VALIDER&nbsp</button>

            </div>
            </div>";
            $html = $infoQuest.$html;
          }else{// répsone libre
            $reponse;
            foreach ($question['reponses'] as $rep) {
              $reponse = $rep; //qu'une seule réponse possible
            }
            $html = "1".$rep['lib'].'%';
            $html .= "
            <div class='quest-container'>
            <h1 id='question'>".$question['lib']."</h1>
            <div id='free-answer-container'>
            <label for='answer'>Réponse:</label>
            <input type='text' id='free-answer-input' name='answer' autocomplete='off' placeholder='Entre une réponse beau mal'>
            </div>
            <div id='valide-container'>
            <button id='validated' class='btn' type='button name='valided' onclick='valideQuestion()'>VALIDER&nbsp</button>
            </div>
            </div>";

          }
          echo $html;
        }else{
          echo "finish";
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
