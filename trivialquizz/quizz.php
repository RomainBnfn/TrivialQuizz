<?php
  session_start();

  require_once "include/index_location.php";

  if(!isset($_GET["theme"])){
    header("Location: ".$index_location);
    exit();
  }
  if(empty($_GET["theme"])){
    header("Location: ".$index_location);
    exit();
  }
  if($_GET["theme"]=="null"){
    header("Location: ".$index_location);
    exit();
  }

  require_once "include/functions.php";
  require_once "include/liaisonbdd.php";

  //récupération du themes
  $theme = tryLoadTheme($bdd,$_GET["theme"]);
  //récupération des quizzes associés au thème
  $quizzes = getAllQuizzesInfosOfTheme($bdd,$theme['id']);


  //nombre de questions dans chaques thèmes
  $nbrQuestByQuizz = getNumbersOfQuestionsOfQuizzes($bdd, $theme['id']);

  //temps et réduction par difficulté par quizzes
  $quizzesDuration = getAllQuizzesDuration($bdd, $theme['id']);
  //$quizzesDuration = array( 1 => array(2*60,20));

  //meilleurs scores globaux
  $scoresGlobaux = getScoreGlobaux($bdd, $theme['id']);

  //meilleurs scores perso
  $scoresPerso = (!empty($_SESSION) && isset($_SESSION['pseudo'])) ? getScorePerso($bdd, $theme['id'], $_SESSION['pseudo']) : null;

  function formatTimeToString($time){
    $min = floor($time % (60 * 60) / (60));
    $sec = floor($time % 60);
    $timeText = "";
    if($min>0) $timeText.=$min.":";
    if($sec>9) $timeText.=$sec."'";
    else $timeText.="0".$sec."'";
    return $timeText;
  }

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <?php require_once "include/header.html"?>
    <link rel="stylesheet" type="text/css" href="css/style-quizz.css">
    <link rel="stylesheet" type="text/css" href="css/card-2.css">
    <title>Choix du quizz - Trivial Quizz</title>
  </head>
  <body>
    <?php require_once "include/navbar.php" ?>
    <section class="bandeau-principal fond-bleu">
      Thème: <?=$theme['nom']?>
    </section>
    <section class="cadre-global">
      <div class="cadre-central">
        <article id="quizz-description" class="center ">
          <p><?=$theme['desc']?></p>
        </article>
        <article>
          <div class="titre1">
            <h1>Les quizzes</h1>
          </div>
          <article class="container">
          <?php
          if(!empty($quizzes))
          {
            ?>
            <div class="card-columns">
              <?php
              foreach ($quizzes as $quizz) {
                if(!empty($nbrQuestByQuizz[$quizz['id']])){
                  ?>
                  <div id="quizz<?=$quizz['id']?>" class="card card-quizz trianglify-background ripple-container dynamic-shadow">
                    <div class="card-body">
                      <h3><?=$quizz['nom']?></h3>
                        <p>Nombre de questions: <span class="badge badge-pill badge-info"><?=$nbrQuestByQuizz[$quizz['id']]?></span><br>
                          Durée: <span class="badge badge-pill badge-info"><?=formatTimeToString($quizzesDuration[$quizz['id']]['temps']*(1-5*$quizzesDuration[$quizz['id']]['malus']/100))?></span>
                          - <span class="badge badge-pill badge-info"><?=formatTimeToString($quizzesDuration[$quizz['id']]['temps'])?></span>
                        </p>
                        <p>
                        <?php
                          if($scoresGlobaux[$quizz['id']]['point'] != -1){
                            ?>
                            <i class="fa fa-trophy" aria-hidden="true"></i>&nbsp; Meilleur score global: <span class="badge badge-pill badge-danger"><?=$scoresGlobaux[$quizz['id']]['point']." (".formatTimeToString($scoresGlobaux[$quizz['id']]['temps']).")"?></span><br>
                            <?php
                            if($isConnected &&  $scoresPerso[$quizz['id']]['point'] != -1){
                              ?>
                              <i class="fa fa-star" aria-hidden="true"></i>&nbsp; Meilleur score perso: <span class="badge badge-pill badge-warning"><?=$scoresPerso[$quizz['id']]['point']." (".formatTimeToString($scoresPerso[$quizz['id']]['temps']).")"?></span>
                              <?php
                            }
                          }
                        ?>
                      </p>
                      <p><?=$quizz['desc']?></p>
                    </div>
                  </div>
                  <?php
                }
              }
              ?>
            </div>
            <?php
          }else{
            ?>
            <p>Il n'y a pas encore de quizzes dans ce thème...</p>
            <?php
          }
          ?>
          </article>
        </article>
      </div>
    </section>

    <!--Choix de la difficulté-->
    <section id="quizz-container">
      <a class="hide control-info ripple-container" id="back" href="quizz.php?theme=<?=$theme['id']?>"><p>RETOUR</p></a>
      <div class="quest-container hide">
        <h1 id="question" class="hide">Choisis une difficulté</h1>
        <div id="difficulty-choice" class="hide">
          <h2 id="difficulty">1 - Tnul</h2>
          <div id="difficulty-container">
            <p>Facile</p>
            <input id="slider-difficulty" type="range" name="difficulty" max="5" min="1" value="1">
            <p>Difficile</p>
          </div>
          <p id="difficulty-desc">(Bouger le curseur)</p>

        </div>
        <button id="btn-begin" class="btn btn-light hide ripple-container" type="button" name="start" onclick='startQuizz()'>Commencer&nbsp</button>
      </div>
    </section>


    <?php require_once "include/script.html" ?>
    <?php require_once "js/trianglify.html" ?>
    <script type="text/javascript" src="js/ripple.js"></script>
    <script type="text/javascript">

    var isConnected = <?php echo ($isConnected==1) ? 'true' : 'false';?>;
    var idTheme = <?=$theme['id']?>;
    var pseudo = "<?php echo ($isConnected==1) ? $_SESSION['pseudo'] : "";?>";
    var idQuizz;
    var score = 0;
    var difficulty = 1; // valeur possible: [|1,5|]

    var btnCheck = 0; //stock l'id du btn correspondant au choix du joueur (difficulté/qcm)

    var duration = <?=json_encode($quizzesDuration)?>; //{qui_id: {temps, malus}, ... , ...}
    var isTimerPaused = true, //timer en pause lors des chargement
      maxDuration, // durée max du quizz en prenant en compte la difficulté
      timer, // objet setInterval
      lastTime, // la durée qu'il reste pour répondre aux questions, actualisé à chaque tour de timer
      timeRef, // temps du début du quizz
      delay = 0, // durée des chargement et pause
      pause = 1500; //durée minimal entre deux questions (histoire que l'ut voit s'il à juste ou pas)

    var numQuestion = 1, //numéro de la question correspond à l'ordre
      bonneRep, //qcm: id du btn de la bonne réponse / réponse libre: chaine réponse
      typeQuest; //qcm: 2 / réponse libre: 1
    var response; //retour de displayQuestion

    var validated = false; // true lorsque l'ut à cliqué une fois sur validé false ensuite,
                           // réinitialisé pour chaque question (empeche de spamé pendant la pause)

    $(document).ready(function(){

      var card = $('.card-quizz');

      /// CLIQUE SUR UN QUIZZ == LANCEMENT DU QUIZZ ///
      $('.card-quizz').click(function(){
        //il faut que la personne soit connecté
        if(!isConnected){
          $('#modalConnexion').modal('show');
        }else{
          // carte cliquée
          var clickedCard = $(this);
          idQuizz = clickedCard.attr('id').substring(5);
          duration = duration[idQuizz];
          //suppression de toutes les autres cartes
          for(var i =0;i<card.length;i++){
            if(card[i]!=clickedCard[0]){
              card[i].remove();
            }
          }

          //agrandissement de la carte cliquée
          $('body').css('overflow','hidden');
          clickedCard.addClass('full-page');

          //remplacement du font du body par celui de la carte cliqué
          //puis suppression de la section de sélèction du quizz
          setTimeout(function(){
            $('html').css('margin-top','0');
            $('body')[0].style.backgroundImage = clickedCard[0].style.backgroundImage;
            $('section')[0].remove();
            $('section')[0].remove();
          },1000);

          //apparition des elements
          var text = $('.quest-container').children();
          for(var i=0;i<text.length;i++){
            spawn(text[i],i,text.length,1000);
          }
        }
      });

      $('#slider-difficulty').on('change', function(){
        switch($(this).val()){
          case "1":
            difficulty = 1;
            $('#difficulty').text("1 - Tnul");
            break;
          case "2":
            difficulty = 2;
            $('#difficulty').text("2 - Tmauvais");
            break;
          case "3":
            difficulty = 3;
            $('#difficulty').text("3 - Tbof");
            break;
          case "4":
            difficulty = 4;
            $('#difficulty').text("4 - Tcho");
            break;
          case "5":
            difficulty = 5;
            $('#difficulty').text("5 - T1génie");
            break;
        }
      })

      $("body").on('keydown', function(e){
        if (window.event) e = window.event;
        else return true;
        var touche = window.event ? e.keyCode : e.which;
        //empécher le retour lors de l'appuie sur la touche supprimer, génant lors de la saisi
        if (touche == 8) {
          if (e.keyCode) e.keyCode=0;
          return false;
        }
        //valider la question
        if(touche == 13 && $('#validated').length != 0){
          valideQuestion();
        }
        return true;
      });
    });

    //fait apparaitre l'élément n, d'un total de nmax éléments après delay millisecondes
    //les nmax éléments apparaisse sur une durée de 1s
    function spawn(element,n,nmax,delay){
      setTimeout(function(){
        element.classList.add('spawn-question');
        $('.hide').removeClass('hide');
      },(delay+n*(1000/nmax)));
    }

    // change l'apparance de l'élément id (apparence sélectionné)
    // et redonne une apparence neutre au dernière élément sélèction
    function check(id){
      if(btnCheck != id){
        $(id).css({
          "border":"solid 2px #333",
          "background-color": "rgba(100, 100, 100, 0.8)",
          "color":"white",
          "box-shadow": "0 0 7px #fff"
        });
        $(btnCheck).css({
          "border":"solid 1px #666",
          "background-color": "rgba(255, 255, 255, 0.4)",
          "color":"black",
          "box-shadow": "none"
        });
        btnCheck = id;
      }
    }

    //initialise le quizz (après choix de la difficulté) et lance la première question
    function startQuizz(){

      //lancement du chargement de la première question
      question(0);

      //faire apparaitre le score et le temps
      displayScoreBoard();

      //lance le spinner de chargement
      $('#validated').append($('<i class="fa fa-spinner fa-pulse"></i>'));


    }

    //affiche le temps et le score et lance le timer
    function displayScoreBoard(){
      var htmlScoreBoard = $(''+
      '<div id="scoreboard" class="control-info">'+
      '<p>Score: <span id="score">0</span></p>'+
      "<p>Temps: <span id='time'>--:--'</span></p>"+
      '</div>');
      $('#quizz-container').append(htmlScoreBoard);

      maxDuration = ( duration.temps * ( 1 - difficulty * duration.malus/ 100 ) ) * 1000;
      timeRef = (new Date().getTime())+1000;

      timer = setInterval(function(){
        if(!isTimerPaused){
          var time = new Date().getTime();
          lastTime = timeRef-time+maxDuration+delay;
          if(lastTime>0){
            $('#time').text(formatTime(lastTime));
          }else{
            endQuizz(true);
          }
        }
      },1000);

    }

    //demande au serveur la question suivante et l'affiche quand il la reçois
    function question(wait){

      $('.plusOne').remove();
      var t0 = new Date().getTime(), t1=0;

      if (window.XMLHttpRequest) {    // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
      } else {                        // code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
      }
      xmlhttp.onreadystatechange=function() {
        if (this.readyState==4 && this.status==200) {
          response = this.responseText;

          t1 = new Date().getTime();

          setTimeout(function(){
            $('.quest-container').children().addClass('disappearance');
            setTimeout(function(){
              $('.quest-container').remove();
              console.log(response);
              if(response == "finish"){
                endQuizz(false);
              }else{
                var t2 = new Date().getTime();
                delay += Math.max(1000,t2-t0);
                isTimerPaused = false;
                typeQuest = response[0];
                bonneRep = response.substring(1,response.indexOf('%'));
                $('#quizz-container').append(response.substring(response.indexOf('%')+1));
                refreshRipple();
                var elements = $('.quest-container').children();
                for(var i=0;i<elements.length;i++){
                  spawn(elements[i],i,elements.length,500);
                }
              }
            },200);
          },Math.max(0,wait-t1+t0));
        }
      }

      xmlhttp.open("GET","ajax/displayQuestion.php?idQuizz="+idQuizz+"&numQuest="+numQuestion+"&difficulty="+difficulty,true);
      xmlhttp.send();
    }

    //fonction lancée au moment du clic lors de la validation d'une question
    //incremente le score si bonne réponse
    function valideQuestion(){

      //le code de cette focntion est exécuté une seule fois par question
      // lors du première appui sur VALIDER les autres appuis vont...
      if(validated){
        // ... ici
        return;
      }
      validated = true;

      numQuestion++;
      question(pause);//chargement de la prochaine question

      //lance le spinner de chargement au bout de pause millisecondes
      setTimeout(function(){
        $('#validated').append($('<i class="fa fa-spinner fa-pulse"></i>'));
      },pause)

      isTimerPaused = true;

      if(typeQuest == 1){ // réponse libre
        if(isEqual($('#free-answer-input').val(),bonneRep)){
          score+=(difficulty-1)/2+1;
          $('#score').text(score);
          $(bonneRep).addClass('right-answer');
          $('#validated').addClass('right-answer');
          var plus = $('<span class="plusOne">+'+((difficulty-1)/2+1)+'</span>');
          $('#quizz-container').append(plus);
        }else{
          $(btnCheck).addClass('bad-answer');
          $('#validated').addClass('bad-answer');
        }
      }else{ //qcm
        if(btnCheck==bonneRep){
          score+=(difficulty-1)/2+1;
          $('#score').text(score);
          $(bonneRep).addClass('right-answer');
          $('#validated').addClass('right-answer');
          var plus = $('<span class="plusOne">+'+((difficulty-1)/2+1)+'</span>');
          $('#quizz-container').append(plus);
        }else{
          $(btnCheck).addClass('bad-answer');
          $('#validated').addClass('bad-answer');
        }
      }
      validated = false;
      btnCheck = null;
    }

    // appelé lorsqu'il n'y a plus de question ou quand le temps est écoulé
    function endQuizz(isTimeOut){

      clearInterval(timer);

      if(isTimeOut){
        $('.quest-container').children().addClass('disappearance');
        setTimeout(function(){
          $('.quest-container').remove();
        },500);
      }

      var time = Math.round((maxDuration-lastTime)/1000+1);
      $('#time').text(formatTime(Math.min(time*1000,maxDuration)));
      $('#scoreboard').addClass('expend');
      $('#back').addClass('continue');

      //sauvgarde le score dans la bdd
      fetch("ajax/addScore.php?point="+score+"&temps="+time+"&diff="+difficulty+"&profil="+pseudo+"&quizz="+idQuizz);
    }

    // test l'égalité des chaine s1 et s2 ne tient pas compte de la casse
    // admet une marge d'erreur de 20%
    function isEqual(s1, s2){
      str1 = s1.toLowerCase();
      str2 = s2.toLowerCase();
      var joker = Math.round(Math.max(str1.length,str2.length)*0.2);
      var i = 0;
      while(joker>0 && i < str1.length && i < str2.length){
        if(str1[i]!=str2[i]){
          joker--;
        }
        i++;
      }
      return (joker > 0 && joker-Math.abs(str1.length-str2.length) > 0);
    }

    // formate timeInMillis en chaine: mm:ss'
    function formatTime(timeInMillis){
      var minute = Math.floor((timeInMillis % (1000 * 60 * 60)) / (1000 * 60));
      var seconde = Math.floor((timeInMillis % (1000 * 60)) / 1000);
      var timeText = "";
      if(minute>0) timeText += minute+":";
      if(seconde>9) timeText += seconde+"'";
      else timeText += "0"+seconde+"'";
      return timeText;
    }

    </script>
  </body>
</html>
