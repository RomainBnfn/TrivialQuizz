<?php
  session_start();

  $base_location = "/trivial/trivialquizz";
  //TODO: Changer ça
  $_SESSION['is_admin'] = true;
  if(!isset($_SESSION['is_admin']))
  {
    //TODO: Changer la location
    header("Location: $base_location/index.php");
    exit();
  } 

  require_once "../include/liaisonbdd.php";
  require_once "../include/functions.php";

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
        <div>
          <?php
            // Nous devons déterminer le nombre de quizz pour savoir quoi afficher
            $nbQuiz = getNbQuizz($bdd);

            // - Il n'y a pas de quiz -
            //
            if ($nbQuiz <= 0)
            {
              echo "Il n'y a encore aucun Quiz de créé. Appuyez sur le bouton ajouter pour remédier à ce grave problème ! :)";
            }
            //  - Il y a au moins un quizz -
            //
            else
            {
              $requete = $bdd -> query("SELECT * FROM quiz ORDER BY th_id");

              while($result = $requete ->fetch())
              {
                $name = $result["qui_nom"];
                $id = $result["qui_id"];
                $desc = $result["qui_desc"];
                // Trouver quel est le nom, couleur du thème..
                $idTheme = $result["th_id"];
                ?>

                <div id="quizzN<?= $id ?>"> <!-- L'id sert pour identifier les différents div des quizz-->
                  <div class="titre2">
                    <div class="cat-title"><?= $name ?></div>
                    <div class="edition">
                      <a href="quizz-edit.php?id=<?= $id ?>">
                        <button type="button" class="btn btn-warning">Edition</button>
                      </a>
                      <button id="suppressionQuizzN<?= $id ?>" type="button" class="btn btn-danger">Supprimer</button>
                    </div>
                  </div>
                  <div><?= $desc ?></div>
                </div>

              <?php
              }
            }
          ?>
        </div>
          <!-- FIN: Liste des Quizz-->
      </div>
      <!-- FIN: Section des Quizz -->
      <br/>
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
        $("#quizzN<?= $id ?>").click(function(){
          fetch("ajax/quizz-delete.php?id=<?= $id ?>")
            .then(()) => {
              $(this).text("");
            })
            .catch(() => {
              // Code called in the future when an errors occurs during the request
            });
        });
        <?php
        }
      ?>
    });
  </script>
</body>
</html>
