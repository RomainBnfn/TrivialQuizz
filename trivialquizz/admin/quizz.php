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

  $_THEMES = getAllThemesInfos($bdd); //Sera utile partout après, autant le charger.
  $_QUIZZES = getAllQuizzesInfos($bdd);

  $messagePasDeQuizz = "Il n'y a encore aucun Quiz de créé. Appuyez sur le bouton ajouter pour remédier à ce grave problème ! :)";
  // On le stock car réutilisé dans le js.
?>
<!doctype html>
<html lang="fr">
<head>
  <title>Panel d'Admin</title>
  <?php require_once "../include/header.html"?>
  <link rel="stylesheet" href="../css/cards.css" />
</head>
<body>
  <?php require_once "../include/navbar.php"?>

  <div class="bandeau-principal fond-bleu">
    Panel d'admin
  </div>

  <div class="cadre-global">
    <div class="cadre-central">

      <?php include "include/admin-navbar.php"?>

      <!-- DEBUT: Section des Quizz-->
      <div>
        <div class="titre1">
          <h1>Les Quizz</h1>
          <button id="boutonCreerQuizz" type="button" class="btn btn-success button-open-modal" data-toggle="modal" data-target="#modalCreationQuizz">
            Créer un Quizz !
          </button>
        </div>

        <!-- DEBUT: Liste des Quizz-->
        <div id="containerListQuizz">
          <?php
            if ($_QUIZZES == null) // Pas de quizz
            {
              echo $messagePasDeQuizz;
            }
            else // Il y a au moins un quizz
            {
              $id_previousTheme = -1;
              foreach ($_QUIZZES as $_QUIZZ)
              {
                if($_QUIZZ['id_theme'] != $id_previousTheme){
                  if($id_previousTheme != -1){ //Si c'est pas le premier
                    echo "</div></div></div>";
                  }
                  $id_previousTheme = $_QUIZZ['id_theme'];
                  ?>
                    <div class="card">
                      <?php $color = $_THEMES["$id_previousTheme"]["couleur"]; ?>
                      <div class="card-header" style="background-color: <?= $color ?>;">
                        <h4><?= $_THEMES["$id_previousTheme"]["nom"] ?></h4>
                      </div>

                      <div class="card-body" style="background: <?= $color?>20;">
                        <div class="card-columns card-columns-sm">
                  <?php
                }
                ?>
                <div class="card" id="quizzN<?= $_QUIZZ["id"] ?>" style="background-color: <?= $color ?>20"> <!-- L'id sert pour identifier les différents div des quizz-->
                  <div class="card-header" style="background-color: <?= $color ?>50">
                    <div class="card-title">
                      <h5><?= $_QUIZZ["nom"] ?></h5>
                    </div>
                  </div>

                  <div class="card-body">
                    <div>Description: <?= $_QUIZZ["desc"] ?></div>
                  </div>

                  <div class="card-footer d-flex justify-content-around">
                    <a href="quizz-edit.php?id=<?= $_QUIZZ["id"] ?>">
                      <button type="button" class="btn btn-warning">
                        <i class="far fa-edit"></i>
                        Édition
                      </button>
                    </a>
                    <button id="suppressionQuizzN<?= $_QUIZZ["id"] ?>" type="button" class="btn btn-danger">
                      <i class="fas fa-trash-alt" style="color: #ffffff;"></i>
                      Supprimer
                    </button>
                  </div>

                </div>

              <?php
              }
            }
          ?>
        </div>
          <!-- FIN: Liste des Quizz-->
      </div>
      <!-- FIN: Section des Quizz -->

    </div>
  </div>
  <?php require_once "../include/script.html"?>
  <?php require_once "modals/quizz-create.php"?>
  <script>
      $(document).ready(function(){
        <?php
          if(!empty($_QUIZZES))
          {
            foreach ($_QUIZZES as $_QUIZZ)
            {
              if (empty($_QUIZZ["id"])) break;
            ?>
            $("#suppressionQuizzN<?= $_QUIZZ["id"] ?>").click(function(){
              fetch("ajax/quizz-delete.php?id=<?= $_QUIZZ["id"] ?>")
                .then((response) => {
                  response.text()
                  .then((resp) => {
                    document.location.reload(true);
                  })
                })
                /* A voir si on met un message d'erreur
                .catch(() => {
                });*/
            });
            <?php
            }
          }
        ?>
      });
  </script>

</body>
</html>
