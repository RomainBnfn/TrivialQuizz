<?php
  session_start();

  //TODO: changer chemin
  $path_index = "/trivial/trivialquizz/index.php";

  if(!isset($_GET["theme"])){
    header("Location: ".$path_index);
    exit();
  }

  require_once "include/functions.php";
  require_once "include/liaisonbdd.php";

  //récupération du themes
  $theme = tryLoadTheme($bdd,$_GET["theme"]);
  //récupération des quizzes associés au thème
  $quizzes = getAllQuizzesInfosOfTheme($bdd,$theme['id']);

  //variable qui permet de revenir à la page où était l'ut avant qu'il se connecte
  $_SESSION["origin"] = "quizz.php?theme=".$theme['id'];

  //nombre de questions dans chaques thèmes
  $nbrQuestByQuizz = getNumbersOfQuestionsOfQuizzes($bdd, $theme['id']);

  //temps et réduction par difficulté par quizzes
  //$quizzesDuration = getAllQuizzezDuration($bdd, $theme['id']);
  $quizzesDuration = array( 2 => array(3*60,20));
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <?php require_once "include/header.html"?>
    <link rel="stylesheet" type="text/css" href="css/style-quizz.css">
    <link rel="stylesheet" type="text/css" href="css/card-2.css">
    <link rel="stylesheet" type="text/css" href="css/test.css">
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
          <h1 class="titre1">Les quizzes</h1>
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
                      <p>Nombre de questions: <span class="badge badge-pill badge-info"><?=$nbrQuestByQuizz[$quizz['id']]?></span></p>
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
      </div>
    </section>

    <!--Choix de la difficulté-->
    <section id="quizz-container">
      <a class="hide control-info ripple-container" id="back" href="quizz.php?theme=<?=$theme['id']?>"><p>RETOUR</p></a>
      <div class="quest-container hide">
        <h1 class="hide">Choix difficulté?</h1>
        <div id="difficulty-choice" class="hide btn-group">
          <button id="btn-dif1" class="btn btn-light ripple-container" type="button" name="dif1" onclick='check("#btn-dif1")'>Handicapé</button>
          <button id="btn-dif2" class="btn btn-light ripple-container" type="button" name="dif2" onclick='check("#btn-dif2")'>Tranquillou</button>
          <button id="btn-dif3" class="btn btn-light ripple-container" type="button" name="dif3" onclick='check("#btn-dif3")'>Milieu</button>
          <button id="btn-dif4" class="btn btn-light ripple-container" type="button" name="dif4" onclick='check("#btn-dif4")'>Aïe Aïe</button>
          <button id="btn-dif5" class="btn btn-light ripple-container" type="button" name="dif5" onclick='check("#btn-dif5")'>7eme ciel</button>
        </div>
        <button id="btn-begin" class="btn btn-light hide ripple-container" type="button" name="start" onclick='startQuizz()'>Commencer&nbsp</button>
      </div>
    </section>


    <?php require_once "include/script.html" ?>
    <?php require_once "js/trianglify.html" ?>
    <script type="text/javascript" src="js/ripple.js"></script>
    <script type="text/javascript">

    var isConnected = <?=$connected?>; //cf navbar.php
    var idTheme = <?=$theme['id']?>;
    var pseudo = "<?=$_SESSION['pseudo']?>";
    var idQuizz;
    var score = 0;
    var difficulty;

    var btnCheck = 0; //stock l'id du btn correspondant au choix du joueur (difficulté/qcm)

    var duration = <?=json_encode($quizzesDuration)?>; //{qui_id: {durée, malus}, ... , ...}
    var isTimerPaused = true, //timer en pause lors des chargement
      maxDuration, // durée max du quizz en prenant en compte la difficulté
      timer, // objet setInterval
      lastTime, // la durée qu'il reste pour répondre aux questions, actualisé à chaque tour de timer
      timeRef, // temps du début du quizz
      delay = 0; // durée des chargement et pause

    var numQuestion = 1, //numéro de la question correspond à l'ordre
      bonneRep, //qcm: id du btn de la bonne réponse / réponse libre: chaine réponse
      typeQuest; //qcm: 2 / réponse libre: 1
    var response; //retour de displayQuestion

    var validated = false; // true lorsque l'ut à cliqué une fois sur validé false ensuite,
                           // réinitialisé pour chaque question (empeche de spamé pendant la pause)

    $(document).ready(function(){

      var card = $('.card-quizz');
      /// CLIQUE SUR UN QUIZZ == LANCEMENT DU QUIZZ ///
      card.on('click',function(){

        //il faut que la personne soit connecté
        if(!isConnected){
          document.location.href="log.php";
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

          //remplacement du font du body par celui de la carte cliquéecho
          //puis suppression de la section de sélèction du quizz
          setTimeout(function(){
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
      //récupérer la difficulté
      if(btnCheck == 0)
      return;
      difficulty = btnCheck.substring(8);

      //faire apparaitre le score et le temps
      displayScoreBoard();

      // lancement d'une question
      $('#btn-begin').append($('<i class="fa fa-spinner fa-spin"></i>'));
      question();
    }

    //affiche le temps et le score et lance le timer
    function displayScoreBoard(){
      var htmlScoreBoard = $(''+
      '<div id="scoreboard" class="control-info">'+
      '<p>Score: <span id="score">0</span></p>'+
      "<p>Temps: <span id='time'>--:--'</span></p>"+
      '</div>');
      $('#quizz-container').append(htmlScoreBoard);

      maxDuration = (duration[0]-(difficulty-1)*duration[1])*1000;
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

    //demande au serveur la question suivante et l'affiche quand il reçois la réponse
    function question(){

      $('.plusOne').remove();
      var t0 = new Date().getTime(), t1=0;

      if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
      } else { // code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
      }
      xmlhttp.onreadystatechange=function() {
        if (this.readyState==4 && this.status==200) {
          response = this.responseText;

          setTimeout(function(){
            $('.quest-container').children().addClass('disappearance');
            setTimeout(function(){
              $('.quest-container').remove();
              if(response == "finish"){
                endQuizz(false);
              }else{
                var t1 = new Date().getTime();
                delay += Math.max(1000,t1-t0);
                isTimerPaused = false;
                typeQuest = response[0];
                bonneRep = response.substring(1,response.indexOf('%'));
                console.log(bonneRep);
                $('#quizz-container').append(response.substring(response.indexOf('%')+1));
                refreshRipple();
                var elements = $('.quest-container').children();
                for(var i=0;i<elements.length;i++){
                  spawn(elements[i],i,elements.length,500);
                }
              }
            },200);
          },Math.min(1000,t1-t0));
        }
      }

      xmlhttp.open("GET","ajax/displayQuestion.php?idQuizz="+idQuizz+"&numQuest="+numQuestion+"&difficulty="+difficulty,true);
      setTimeout(function(){
        xmlhttp.send();
      },2000);

    }

    //fonction lancée au moment du clic lors de la validation d'une question
    //incremente le score si bonne réponse
    function valideQuestion(){
      if(validated){
        return;
      }
      validated = true;
      $('#validated').append($('<i class="fa fa-spinner fa-spin"></i>'));
      isTimerPaused = true;
      if(typeQuest == 1){
        if(isEqual($('#free-answer-input').val(),bonneRep)){
          score++;
          $('#score').text(score);
          $(bonneRep).addClass('right-answer');
          $('#validated').addClass('right-answer');
          var plus = $('<span class="plusOne">+1</span>');
          $('#quizz-container').append(plus);
        }else{
          $(btnCheck).addClass('bad-answer');
          $('#validated').addClass('bad-answer');
        }
      }else{
        if(btnCheck==bonneRep){
          score++;
          $('#score').text(score);
          $(bonneRep).addClass('right-answer');
          $('#validated').addClass('right-answer');
          var plus = $('<span class="plusOne">+1</span>');
          $('#quizz-container').append(plus);
        }else{
          $(btnCheck).addClass('bad-answer');
          $('#validated').addClass('bad-answer');
        }
      }
      numQuestion++;
      validated = false;
      btnCheck = null;
      question();
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
      var time = Math.round((maxDuration-lastTime)/1000);
      $('#time').text(formatTime(time*1000));
      $('#scoreboard').addClass('expend');
      $('#back').addClass('continue');

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
