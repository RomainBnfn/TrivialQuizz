<!doctype html>
<html lang="fr">
<head>
  <title>Panel d'Admin</title>
  <?php require_once "include/header.html"?>
</head>
<body>
  <?php require_once "include/navbar.php"?>
  <div class="bandeau-principal fond-bleu">Panel d'admin</div>
  <div class="cadre-global">
    <div class="cadre-central">

      <h2>Catégories</h2>
      <span>Ajouter</span>
      <div>
        <!--Liste des Catégories-->
        <span>Test de Cat</span> <span>Editer</span> <span>Supprimer</span>
        <div>Description</div>
      </div>
      <h2>Quizz</h2>
      <span>Ajouter</span>
      <div>
        Liste des Quizz
      </div>
    </div>
  </div>
  <?php require_once "include/script.html"?>
<!--
require_once( ABSPATH . 'wp-admin/includes/admin.php' );
--></body>
</html>
