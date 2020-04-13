<?php
  session_start();

  //TODO: Changer ça
  $base_location = "/trivial/trivialquizz";
  $_SESSION['is_admin'] = true;
  if(!isset($_SESSION['is_admin']))
  {
    //TODO: Changer la location
    header("Location: $base_location/index.php");
    exit();
  }

  require_once "../include/liaisonbdd.php";
  require_once "../include/functions.php";

  $_THEMES = getAllThemesInfos($bdd);

?>
<!doctype html>
<html lang="fr">
<head>
  <title>Panel d'Admin</title>
  <?php require_once "../include/header.html"?>
  <link rel="stylesheet" href="../css/cards.css" />
  <link rel="stylesheet" href="../css/modal.css" />
</head>
<body>
  <?php require_once "../include/navbar.php"?>

  <div class="bandeau-principal fond-bleu">
    Panel d'admin
  </div>

  <div class="cadre-global">
    <div class="cadre-central">

      <!-- DEBUT: Section des Thèmes-->
      <div>

        <div class="titre1">
          <h1>Les Thèmes</h1>
          <button id="boutonCreerTheme" type="button" class="btn btn-success button-open-modal"  data-toggle="modal" data-target="#modalCreationTheme">
            Créer un Thème !
          </button>
        </div>

        <!-- DEBUT: Liste des Thèmes-->
        <div>
          <p>Les six premiers thèmes ne sont qu'éditable, ce sont ceux présents sur la roue de l'accueil du site !</p>

        <div class="card-columns">
          <?php
            $_NBOFQUIZZ = getNumbersOfQuizzesOfThemes($bdd);
            foreach($_THEMES as $_THEME)
            {
              ?>
              <div id="themeN<?= $_THEME["id"]?>" class="card">

                <div class="card-header" style="background-color: <?= $_THEME['couleur'] ?>;">
                </div>

                <div class="card-body" style="background: <?= $_THEME['couleur'] ?>20;">
                  <h5><?= $_THEME["nom"] ?></h5>
                  <div class="card-text"><?php
                  if(!empty($_NBOFQUIZZ[$_THEME["id"]])){
                    if($_NBOFQUIZZ[$_THEME["id"]]==1){
                      echo '<span class="badge badge-pill badge-info">1</span> Quizz Associé !';
                    }
                    else {
                      echo '<span class="badge badge-pill badge-info">'.$_NBOFQUIZZ[$_THEME["id"]].'</span> Quizzes associés !';
                    }
                  }
                  else{
                      echo '<span class="badge badge-pill badge-danger">0</span> Quizz Associé :(';
                  } ?></div>
                  <br/>
                  <p class="card-text"><?= $_THEME["desc"] ?></p>
                </div>


                <div class="card-footer d-flex justify-content-around" style="background: <?= $_THEME['couleur'] ?>60;">
                  <a href="theme-edit.php?id=<?= $_THEME["id"] ?>">
                    <button type="button" class="btn btn-warning btn-border-blanc">
                      <i class="far fa-edit"></i>
                      Edition
                    </button>
                  </a>
                  <?php if($_THEME["is_Principal"] == 0){
                    // On ne met que le boutton de suppression pour les Thèmes non principaux?>
                    <button id="suppressionThemeN<?= $_THEME["id"]?>" type="button" class="btn btn-danger btn-border-blanc">
                      <i class="fas fa-trash-alt" style="color: #ffffff;"></i>
                      Supprimer
                    </button>
                  <?php } ?>
                </div>
              </div>
            <?php
            }
          ?>
          </div>

        </div>
          <!-- FIN: Liste des Thèmes-->
      </div>
      <!-- FIN: Section des Thèmes -->

  <?php require_once "../include/script.html"?>
  <?php require_once "modals/theme-create.php"?>
  <script>
      $(document).ready(function(){
        <?php
          foreach ($_THEMES as $_THEME)
          {
            if (empty($_THEME["id"])) break;
            if ($_THEME["is_Principal"] == 1) continue;
          ?>
          $("#suppressionThemeN<?= $_THEME["id"] ?>").click(function(){
            fetch("ajax/theme-delete.php?id=<?= $_THEME["id"] ?>")
              .then((response) => {
                $("#themeN<?= $_THEME["id"] ?>").text("");
                $("#themeN<?= $_THEME["id"] ?>").css("display", "none");
                // Pas besoin de voir s'il reste encore des thèmes,
                // il y aura toujours les principaux.
              })
          });
          <?php
          }
        ?>
      });
  </script>

</body>
</html>
