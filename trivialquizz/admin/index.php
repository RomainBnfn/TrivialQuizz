<?php
  session_start();
  /*
  if(!isset($_SESSION['is_admin']))
  {
    //TODO: Changer la location
    header("Location: /github/trivialquizz/index.php");
    exit();
  }
  */
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
            $requete = $bdd -> query("SELECT COUNT(*) FROM theme");
            $result = $requete -> fetch();
            $nbTheme = $result[0];
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
            <button type="button" class="btn btn-success">Ajouter</button>
          </div>
        </div>

        <!-- DEBUT: Liste des Quizz-->
        <div>
          <?php
            $requete = $bdd -> query("SELECT COUNT(*) FROM quiz");
            $result = $requete -> fetch();
            $nbQuiz = $result[0];
            if ($nbQuiz <= 0)
            {
              echo "Il n'y a encore aucun Quiz de créé. Appuyez sur le bouton ajouter pour remédier à ce grave problème ! :)";
            }
            else
            {
              $requete = $bdd -> query("SELECT * FROM quiz");
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
          <!-- FIN: Liste des Quizz-->
      </div>
      <!-- FIN: Section des Quizz -->
      <br/>
    </div>
  </div>

  <?php require_once "../include/script.html"?>
  <script>
    $(document).ready(function() {

    });
  </script>
</body>
</html>
