<?php
  session_start();
  $index_location = "/github/trivialquizz/index.php";
  /*
  if(!isset($_SESSION['is_admin']))
  {
    //TODO: Changer la location
    header("Location: ".$index_location);
    exit();
  }
  */
  //require_once "../include/liaisonbdd.php";
  require_once "../include/functions.php";
  if(empty($_GET['id']) || !is_numeric($id) )
  {
    //TODO: Changer la location
    header("Location: ".$index_location);
    exit();
  }
  $id = $_GET['id'];
  $requete = $bdd -> prepare("SELECT * FROM quizz WHERE qui_id = ?");
  $requete -> execute(array($id));
  $result = $requete -> fetch();
  if(empty($result))
      {
          echo "<h3>Ce film n'existe pas...</h3>";
          echo "<title>Erreur</title>";
          exit();
      }
      // Tout est bon, le film existe :
      $title = $result["TITLE"];
      $director = $result["PRODUCER"];
      $annee = $result["YEAR"];
      $long = $result["LONG_DESC"];
      $image = $result["IMAGE"];
      $user = $result["LOGIN_UTILISATEUR"];

      if(!fopen("images/$image", 'rb'))
      {
          echo "<h3>L'image n'existe pas..</h3>";
          echo "<title>Erreur</title>";
          exit();
      };
      echo "<title>MyMovies - $title</title>";
?>
<!doctype html>
<html lang="fr">
<head>
  <title>Edition de Quizz</title>
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
<!--
require_once( ABSPATH . 'wp-admin/includes/admin.php' );
--></body>
</html>
