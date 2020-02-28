
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
        <div>Catégories</div>
        <div>
          <button type="button" class="btn btn-success">Ajouter</button>
        </div>
      </div>
      <!--Liste des Catégories-->
      <div>
        <div class="titre2">
          <div class="cat-title">Nom de cat</div>
          <div class="edition">
            <button type="button" class="btn btn-warning">Edition</button>
            <button type="button" class="btn btn-danger">Supprimer</button>
          </div>
        </div>
        <div>Description</div>
      </div>

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
