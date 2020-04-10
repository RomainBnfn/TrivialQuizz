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
    <h2 class="quest" style="font-weight: bold; padding-bottom: 20px;">Question 1: Combien d'argent le BDE a-t-il perdu à cause du coronavirus?</h1>
    <h4 class="quest titre3">Réponse 1: Rien du tout </h4>
    <h4 class="quest titre3">Réponse 2: 0.01E </h4>
    <h4 class="quest titre3">Réponse 3: 1000E </h4>
    <h4 class="quest titre3">Réponse 4: Ton WEI </h4>

    <?php require_once "include/script.html" ?>
    <?php require_once "js/trianglify.html" ?>
    <script type="text/javascript" src="js/ripple.js"></script>
    <script type="text/javascript">
      $(document).ready(function(){

        var card = $('.card');
        card.on('click',function(){
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
            $('section').remove();
          },1000);

          //apparition de l
          var text = $('.quest');
          for(var i=0;i<text.length;i++){
            spawn(text[i],i);
          }
        });

      });


      function spawn(element,i){
        setTimeout(function(){
          element.classList.add('spawn-question');
        },(900+i*250));
      }

    </script>
  </body>
</html>
