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
          <div>Les Questions</div>
          <div>
            <a href="<?=$base_location?>/admin/quizz-create.php">
              <button type="button" class="btn btn-success">Ajouter</button>
            </a>
          </div>
        </div>

        
      </div>
      <!-- FIN: Section des Quizz -->

    </div>
  </div>

  <?php require_once "../include/script.html"?>
<script>
  $(document).ready(function(){
    <?php
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
    ?>
  });
</script>

</body>
</html>
