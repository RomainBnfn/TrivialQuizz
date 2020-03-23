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

      <!-- DEBUT: Section des Thèmes-->
      <div>
        <div class="titre1">
          <div>Thèmes</div>
          <div>
            <button type="button" class="btn btn-success">Ajouter</button>
          </div>
        </div>

        <!-- DEBUT: Liste des Thèmes-->
        <div>
          <?php
            $nbTheme = getNbTheme($bdd);
            if ($nbTheme <= 0)
            {
              echo "Il n'y a encore aucun Thème de créé. Vous devez faire quelque chose, vite ! Appuyez sur le bouton 'Ajouter' !!";
            }
            else
            {
              $requete = $bdd -> query("SELECT * FROM theme");
              while($result = $requete ->fetch())
              {
                $name = $result["th_nom"];
                $id = $result["th_id"];
                $desc = $result["th_description"];
                $couleur = $result["th_couleur"];
                ?>
                <div>
                  <div class="titre2">
                    <div class="cat-title"><?= $name ?></div>
                    <div class="edition">
                      <button type="button" class="btn btn-warning">Edition</button>
                      <button type="button" class="btn btn-danger">Supprimer</button>
                    </div>
                  </div>
                  <div><?= $desc ?></div>
                </div>
              <?php
              }
            }
          ?>
        </div>
          <!-- FIN: Liste des Thèmes-->
      </div>
      <!-- FIN: Section des Thèmes -->

<!-- ===================================================================== -->
      <!-- DEBUT: Section des Quizz-->
      <div>
        <div class="titre1">
          <div>Les Quizz</div>
          <div>
            <?php
              //Nous devons déterminer l'id du potentiel futur quizz :
              $requete = $bdd -> query("SELECT MAX(qui_id) FROM quiz");
              $result = $requete -> fetch();
              $futurIdQuiz = $result[0] + 1;
            ?>
            <a href="<?=$base_location?>/admin/quizz-create.php?id=<?= $futurIdQuiz ?>">
              <button type="button" class="btn btn-success">Ajouter</button>
            </a>
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
                  <div><?= $_QUIZZ["desc"] ?></div>
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
  <script>
      $(document).ready(function(){
        <?php
          foreach (getAllQuizzID($bdd) as $id)
          {
            if (empty($id)) break;
          ?>
          $("#suppressionQuizzN<?= $id ?>").click(function(){
            fetch("ajax/quizz-delete.php?id=<?= $id ?>")
              .then((response) => {
                response.text()
                .then((resp) => {
                  if (resp != 0)
                  {
                    $("#quizzN<?= $id ?>").text("");
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
