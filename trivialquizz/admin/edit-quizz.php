<?php
  session_start();
  //TODO: Changer la location
  $index_location = "/github/trivialquizz/admin/index.php";

  //TODO: Changer àa
  $_SESSION["is_admin"] = true;
  if(!isset($_SESSION['is_admin']))
  {
    header("Location: ".$index_location);
    exit();
  }

  require_once "../include/liaisonbdd.php";
  require_once "../include/functions.php";

  if(empty($_GET['id']) || !is_numeric($_GET['id']) )
  {
    header("Location: ".$index_location);
    exit();
  }
  $id = $_GET['id'];
  $requete = $bdd -> prepare("SELECT * FROM quiz WHERE qui_id = ?");
  $requete -> execute(array($id));
  $result = $requete -> fetch();
  if(empty($result))
  {
    header("Location: ".$index_location);
    exit();
  }
  // Tout est ok, le quiz existe
  $nom = $result["qui_nom"];
  $description = $result["qui_desc"];
  $id_theme = $result["th_id"];

  // Le thème :
  $requete = $bdd -> query("SELECT * FROM theme WHERE th_id =".$id_theme);
  $result = $requete -> fetch();
  $theme = $result["th_nom"];
  $couleur = $result["th_couleur"];

  // Les  questions :
  $requete = $bdd -> query(
    "SELECT * FROM questions
     WHERE qui_id IN (
       SELECT qui_id IN QUIZ_QUEST
       WHERE que_id = $id)"
     );
  $result_questions = $result -> fetchAll();
?>
<!doctype html>
<html lang="fr">
<head>
  <title>Edition de Quizz</title>
  <?php require_once "../include/header.html"?>
</head>
<body>
  <?php require_once "../include/navbar.php"?>
  <!-- TODO: Ajouter une couleur en fct de $couleur -->
  <div class="bandeau-principal">Edition de Quizz : <?= $nom ?></div>

  <div class="cadre-global">
    <div class="cadre-central">

      <!-- DEBUT : Cadre des options générales -->
      <div>
        <div class="titre1">
          <div>Général</div>
          <div>
            <button type="button" class="btn btn-danger">Supprimer le quizz</button>
          </div>
        </div>

        <div>
          Liste des options possibles, des statistiques moyennes ?...
        </div>
      </div>
      <!-- FIN : Cadre des options générales -->

      <!-- DEBUT : Cadre de la liste des Questions -->
      <div>
        <div class="titre1">
          <div>Les questions</div>
          <div>
            <button type="button" class="btn btn-success">Ajouter une question</button>
          </div>
        </div>

        <div>
          <?php
            foreach ($result_questions as $infos_question) {
              //AJOUTER : DISPLAY
            }
          ?>
        </div>
      </div>
      <!-- FIN : Cadre de la liste des Questions -->

    </div>
  </div>

  <?php require_once "../include/script.html"?>
</body>
</html>
