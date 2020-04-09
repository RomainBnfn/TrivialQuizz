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
                <div class="card trianglify-background ripple-ontainer dynamic-shadow">
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

    <?php require_once "include/script.html" ?>
    <?php require_once "js/trianglify.html" ?>
    <script type="text/javascript" src="js/ripple.js"></script>
    </script>
  </body>
</html>
