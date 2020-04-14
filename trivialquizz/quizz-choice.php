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
  $_SESSION["origin"] = "quizz-choice.php?theme=".$theme['id'];

  //nombre de questions dans chaques thèmes
  $nbrQuestByQuizz = getNumbersOfQuestionsOfQuizzes($bdd, $theme['id']);

  //temps et réduction par difficulté par quizzes
  //$quizzeDuration = getAllQuizzezDuration($bdd, $theme['id']);

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
      <a class="hide control-info ripple-container" id="back" href="quizz-choice.php?theme=<?=$theme['id']?>"><p>RETOUR</p></a>
      <div class="quest-container hide">
        <h1 class="hide">Choix difficulté?</h1>
        <div id="difficulty-choice" class="hide btn-group">
          <button id="btn-dif1" class="btn btn-light ripple-container" type="button" name="dif1" onclick='check("#btn-dif1")'>Handicapé</button>
          <button id="btn-dif2" class="btn btn-light ripple-container" type="button" name="dif2" onclick='check("#btn-dif2")'>Tranquillou</button>
          <button id="btn-dif3" class="btn btn-light ripple-container" type="button" name="dif3" onclick='check("#btn-dif3")'>Milieu</button>
          <button id="btn-dif4" class="btn btn-light ripple-container" type="button" name="dif4" onclick='check("#btn-dif4")'>Aïe Aïe</button>
          <button id="btn-dif5" class="btn btn-light ripple-container" type="button" name="dif5" onclick='check("#btn-dif5")'>7eme ciel</button>
        </div>
        <button id="btn-begin" class="btn btn-light hide ripple-container" type="button" name="start" onclick='startQuizz()'>Commencer</button>
      </div>
    </section>


    <?php require_once "include/script.html" ?>
    <?php require_once "js/trianglify.html" ?>
    <script type="text/javascript" src="js/ripple.js"></script>
    <script type="text/javascript">

    var isConnected = <?=$connected?>; //cf navbar.php
    var idQuizz;
    var btnCheck = 0;
    var difficulty;
    var score = 0;
    var timeRef = 0;

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

    function spawn(element,n,nmax,delay){
      setTimeout(function(){
        element.classList.add('spawn-question');
        $('.hide').removeClass('hide');
      },(delay+n*(1000/nmax)));
    }

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

    function startQuizz(){
      //récupérer la difficulté
      if(btnCheck == 0)
      return;
      difficulty = btnCheck.substring(8);

      //faire disparaire les boutons de choix de difficulté
      //$('.quest').children().removeClass('spaw-question');
      $('.quest-container').children().addClass('disappearance');
      setTimeout(function(){
        $('.quest-container').remove();
        question();
      },200);

      //faire apparaitre le score et le temps
      displayScoreBoard();
    }

var timer;
    function displayScoreBoard(){
      var htmlScoreBoard = $(''+
      '<div id="scoreboard" class="control-info">'+
        '<p>Score: <span id="score">0</span></p>'+
        "<p>Temps: <span id='time'>00'</span></p>"+
        '</div>');
        $('#quizz-container').append(htmlScoreBoard);

        timeRef = (new Date().getTime())+1000;
        timer = setInterval(function(){
          var now = new Date().getTime();
          var time = now-timeRef;
          var minute = Math.floor((time % (1000 * 60 * 60)) / (1000 * 60));
          var seconde = Math.floor((time % (1000 * 60)) / 1000);
          var timeText = "";
          if(minute>0) timeText += minute+":";
          if(seconde>9) timeText += seconde+"'";
          else timeText += "0"+seconde+"'";
          $('#time').text(timeText);
        },1000);
      }

      var numQuestion = 1;
      var bonneRep;
      var typeQuest;
      function question(){
        if (window.XMLHttpRequest) {
          // code for IE7+, Firefox, Chrome, Opera, Safari
          xmlhttp=new XMLHttpRequest();
        } else { // code for IE6, IE5
          xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function() {
          if (this.readyState==4 && this.status==200) {
            if(this.responseText == "finish"){
                endQuizz();
            }else{
              typeQuest = this.responseText[0];
              bonneRep = this.responseText.substring(1,this.responseText.indexOf('%'));
              console.log(bonneRep);
              $('#quizz-container').append(this.responseText.substring(this.responseText.indexOf('%')+1));
              refreshRipple();
              var elements = $('.quest-container').children();
              for(var i=0;i<elements.length;i++){
                spawn(elements[i],i,elements.length,500);
              }
            }
          }
        }
        $('.plusOne').remove();
        xmlhttp.open("GET","ajax/displayQuestion.php?idQuizz="+idQuizz+"&numQuest="+numQuestion+"&difficulty="+difficulty,true);
        xmlhttp.send();

      }

      var validated = false;
      function valideQuestion(){
        if(validated){
          return;
        }
        validated = true;
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
        setTimeout(function(){
          $('.quest-container').children().addClass('disappearance');
          setTimeout(function(){
            $('.quest-container').remove();
            validated = false;
            btnCheck = null;
            question();
          },200);
        },1100);
      }

      function endQuizz(){
        clearInterval(timer);
        $('#scoreboard').addClass('expend');
        $('#back').addClass('continue')
      }

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


    </script>
  </body>
</html>
