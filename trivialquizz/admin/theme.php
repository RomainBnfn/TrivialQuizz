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

  $_THEMES = getAllThemesInfos($bdd);

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

      <!-- DEBUT: Section des Thèmes-->
      <div>
        <div class="titre1">
          <div>Thèmes</div>
          <a href="<?=$base_location?>/admin/theme-create.php">
            <button type="button" class="btn btn-success">Ajouter</button>
          </a>
        </div>

        <!-- DEBUT: Liste des Thèmes-->
        <div>
          <p>Les six premiers thèmes ne sont qu'éditable, ce sont ceux présents sur la roue de l'accueil du site !</p>
          <?php
            foreach($_THEMES as $_THEME)
            {
              ?>
              <div id="themeN<?= $_THEME["id"]?>">
                <div class="titre2">
                  <div class="cat-title"><?= $_THEME["nom"] ?></div>
                  <div class="edition">
                    <a href="theme-edit.php?id=<?= $_THEME["id"] ?>">
                      <button type="button" class="btn btn-warning">Edition</button>
                    </a>
                    <?php if($_THEME["is_Principal"] == 0){
                      // On ne met que le boutton de suppression pour les Thèmes non principaux?>
                      <button id="suppressionThemeN<?= $_THEME["id"]?>" type="button" class="btn btn-danger">Supprimer</button>
                    <?php } ?>
                  </div>
                </div>
                <div><?= $_THEME["desc"] ?></div>
              </div>
            <?php
            }
          ?>
        </div>
          <!-- FIN: Liste des Thèmes-->
      </div>
      <!-- FIN: Section des Thèmes -->

  <?php require_once "../include/script.html"?>
  <script>
      $(document).ready(function(){
        <?php
          foreach ($_THEMES as $_THEME)
          {
            if (empty($_THEME["id"])) break;
            if ($_THEME["is_Principal"] == 1) continue;
          ?>
          $("#suppressionThemeN<?= $_THEME["id"] ?>").click(function(){
            fetch("ajax/theme-delete.php?id=<?= $_THEME["id"] ?>")
              .then((response) => {
                $("#themeN<?= $_THEME["id"] ?>").text("");
                // Pas besoin de voir s'il reste encore des thèmes,
                // il y aura toujours les principaux.
              })
          });
          <?php
          }
        ?>
      });
  </script>

</body>
</html>