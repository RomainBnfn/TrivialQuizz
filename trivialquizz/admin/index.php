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
  //require_once "../include/liaisonbdd.php";
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
  <div class="bandeau-principal fond-bleu">Panel d'admin</div>
  <div class="cadre-global">
    <div class="cadre-central">

      <div class="titre1">
        <div>Thèmes</div>
        <div>
          <button type="button" class="btn btn-success">Ajouter</button>
        </div>
      </div>

      <!--Liste des Thèmes-->
      <?php
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
      ?>
      <h2>Quizz</h2>
      <span>Ajouter</span>
      <div>
        Liste des Quizz
      </div>
    </div>
  </div>
  <?php require_once "../include/script.html"?>
</body>
</html>
