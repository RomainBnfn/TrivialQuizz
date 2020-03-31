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
          <div>Les Quizz</div>
          <div>
            <button id="boutonCreerQuizz" type="button" class="btn btn-success" data-toggle="modal" data-target="#modalCreationQuizz">
              Créer un Quizz !
            </button>
          </div>
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
              foreach ($_QUIZZES as $_QUIZZ)
              {
                ?>
                <div id="quizzN<?= $_QUIZZ["id"] ?>"> <!-- L'id sert pour identifier les différents div des quizz-->
                  <div class="titre2">
                    <div class="cat-title"><?= $_QUIZZ["nom"] ?></div>
                    <div class="edition">
                      <a href="quizz-edit.php?id=<?= $_QUIZZ["id"] ?>">
                        <button type="button" class="btn btn-warning">Edition</button>
                      </a>
                      <button id="suppressionQuizzN<?= $_QUIZZ["id"] ?>" type="button" class="btn btn-danger">Supprimer</button>
                    </div>
                  </div>
                  <div>Description: <?= $_QUIZZ["desc"] ?></div>
                  <div>Thème: <?= loadThemeFromTab($_THEMES, $_QUIZZ["id_theme"])["nom"] ?></div>
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
                    if (resp != 0)
                    {
                      $("#quizzN<?= $_QUIZZ["id"] ?>").text("");
                    }
                    else
                    {
                      $("#containerListQuizz").text("<?= $messagePasDeQuizz ?>");
                    }
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
