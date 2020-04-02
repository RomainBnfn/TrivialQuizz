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
  $theme = getTheme($bdd,$_GET["theme"]);
  //récupération des quizzes associés au thème
  $quizz = getAllQuizzesInfosOfTheme($bdd,$_GET["theme"]);

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <?php require_once "include/header.html"?>
    <title>Choix du quizz - Trivial Quizz</title>
  </head>
  <body>
    <?php require_once "include/navbar.php" ?>
    <section class="bandeau-principal">
      Thème: <?=$theme['nom']?>
    </section>
    <section class="cadre-global">
      <div class="cadre-central">
      </div>
    </section>

    <?php require_once "include/script.html" ?>
  </body>
</html>
