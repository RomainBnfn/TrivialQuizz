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

      <!-- DEBUT: Section des Quizz-->
      <div>
        <div class="titre1">
          <h1>Les Questions</h1>
          <button id="boutonCreerQuizz" type="button" class="btn btn-success button-open-modal" data-toggle="modal" data-target="#modalCreationQuizz">
            Créer un Quizz !
          </button>
        </div>

        <div class="container">

          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <!-- /.box-header -->
                <div class="box-body">

                </div>
                <!-- /.box-body -->
              </div>
              <!-- /.box -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.container -->
      </div>
      <!-- FIN: Section des Quizz -->

    </div>
  </div>

  <?php require_once "../include/script.html"?>

<script>
  $(function() {
  $('.selectpicker').selectpicker();
  });
</script>

</body>
</html>
