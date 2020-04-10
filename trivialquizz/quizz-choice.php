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
    <section class="bandeau-principal">
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
                ?>
                <div class="card trianglify-background ripple-container dynamic-shadow">
                  <div class="card-body">
                    <h3><?=$quizz['nom']?></h3>
                    <p><?=$quizz['desc']?></p>
                  </div>
                </div>
                <?php
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
      <a class="hide control-info" id="back" href="quizz-choice.php?theme=<?=$theme['id']?>"><p>RETOUR</p></a>
      <div class="quest">
        <h1 id="question" class="hide">Choix difficulté?</h1>
        <div id="difficulty-choice" class="hide">
          <button id="btn-easy" class="btn btn-light" type="button" name="easy" onclick='check("#btn-easy")'>Facile</button>
          <button id="btn-hard" class="btn btn-light" type="button" name="hard" onclick='check("#btn-hard")'>Difficile</button>
        </div>
        <button id="btn-begin" class="btn btn-light hide" type="button" name="start" onclick='startQuizz()'>Commencer</button>
      </div>
    </section>


    <?php require_once "include/script.html" ?>
    <?php require_once "js/trianglify.html" ?>
    <script type="text/javascript" src="js/ripple.js"></script>
    <script type="text/javascript">
      var isConnected = <?=$connected?>; //cf navbar.php

      $(document).ready(function(){

        var card = $('.card');

        /// CLIQUE SUR UN QUIZZ ///
        card.on('click',function(){

          //il faut que la personne soit connecté
          if(!isConnected){
            document.location.href="log.php";
          }else{
            // carte cliquée
            var clickedCard = $(this);

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
            var text = $('.quest').children();
            for(var i=0;i<text.length;i++){
              spawn(text[i],i);
            }
          }
        });
      });

      function spawn(element,i){
        setTimeout(function(){
          element.classList.add('spawn-question');
          $('.hide').removeClass('hide');
        },(900+i*250));
      }

      var btnCheck = 0;
      function check(id){
        if(btnCheck != id){
          $(id).css({
            "border":"solid 2px #666",
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

      const DIFFICULTY_HARD = 5;
      const DIFFICULTY_EASY = 0;
      var difficulty;
      var score = 0;
      function startQuizz(){
        //récupérer la difficulté
        if(btnCheck == 0)
          return;
        if(btnCheck == "#btn-easy")
          difficulty = DIFFICULTY_EASY;
        else
          difficulty = DIFFICULTY_HARD;

        //faire disparaire les boutons de choix de difficulté
        //$('.quest').children().removeClass('spaw-question');
        $('.quest').children().addClass('disappearance');
        $('#difficulty-choice').remove();
        $('#btn-begin').remove();

        //faire apparaitre le score et le temps
        displayScoreBoard();

        //faire apparaitre la première question

      }

      var timeRef = 0;
      function displayScoreBoard(){
        var htmlScoreBoard = $(''+
          '<div id="scoreboard" class="control-info">'+
            '<p>Score: <span id="score">0</span></p>'+
            "<p>Temps: <span id='time'>0:00'</span></p>"+
          '</div>');
        $('#quizz-container').append(htmlScoreBoard);

        timeRef = new Date().getTime();
        var x = setInterval(function(){
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

      function nextQuestion(){

      }
    </script>
  </body>
</html>
