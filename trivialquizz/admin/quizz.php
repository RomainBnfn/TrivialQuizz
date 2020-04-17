<?php
  session_start();

  require_once "../include/index_location.php";

  if(!isset($_SESSION['is_admin']))
  {
    header("Location: $index_location/index.php");
    exit();
  }

  require_once "../include/liaisonbdd.php";
  require_once "../include/functions.php";

  $_THEMES = getAllThemesInfos($bdd); //Sera utile partout après, autant le charger.
  $_QUIZZES = getAllQuizzesInfos($bdd);

  $_NBQUESTIONS = getNumbersOfQuestionsOfAllQuizzes($bdd);

  $messagePasDeQuizz = "Il n'y a encore aucun Quiz de créé. Appuyez sur le bouton ajouter pour remédier à ce grave problème ! :)";
  // On le stock car réutilisé dans le js.
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

      <a class="blue-text" href="../index.php">
        <i class="fas fa-arrow-left" style="height: 2.5em;"></i>
        Retour
      <a/>

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
              foreach ($_QUIZZES as $id => $_QUIZZ)
              {
                if($_QUIZZ['id_theme'] != $id_previousTheme){ // Si c'est pas le premier changement de Thème : On ferme la "Card".
                  if($id_previousTheme != -1){ //Si c'est pas le premier
                    echo "</div></div></div>";
                  }
                  $id_previousTheme = $_QUIZZ['id_theme'];
                  ?>
                    <div class="card">
                      <?php $color = $_THEMES["$id_previousTheme"]["couleur"]; ?>
                      <div onclick="collapseOrShow(<?= $id_previousTheme ?>)" class="d-flex justify-content-between card-header" style="background-color: <?= $color ?>;">
                        <h4>
                          <?= $_THEMES["$id_previousTheme"]["nom"] ?>
                        </h4>

                        <i id="cardQuizzSymbolN<?= $id_previousTheme ?>" class="fas fa-angle-double-up"></i>
                      </div>

                      <div id="cardQuizzOfThemeN<?= $id_previousTheme ?>" class="card-body" style="background: <?= $color?>40;">
                        <div class="card-columns-sm card-columns">
                  <?php
                }
                ?>
                <div class="card" id="quizzN<?= $_QUIZZ["id"] ?>" style="background-color: <?= $color ?>20"> <!-- L'id sert pour identifier les différents div des quizz-->
                  <div class="card-header" style="background-color: <?= $color ?>50">
                    <div class="card-title">
                      <h5>
                        <?= $_QUIZZ["nom"] ?>
                      </h5>
                      <span class="badge badge-pill badge-primary">
                        <?=  (isset($_NBQUESTIONS[$id])) ? $_NBQUESTIONS[$id] : 0 ?>
                        Questions
                      </span>

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
    function collapseOrShow(id){
      var el = document.getElementById("cardQuizzOfThemeN"+id),
          symb = document.getElementById("cardQuizzSymbolN"+id);
      if (el.style.display == "none")
      {
        el.style.display = "block";
        symb.className = "fas fa-angle-double-up";
        return;
      }
      el.style.display = "none";
      symb.className = "fas fa-angle-double-down";
    }
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
