<?php
  /*  Cette page est accessible via le pannel d'administration. Lors de la création
   * d'un quizz, le futur id est passé par méthode GET, cette page s'ouvre et
   * l'administrateur peut rentrer les paramètres généraux qui viendront remplir
   * la BDD.
   *  Si cette page n'est pas remplie et envoyer, le quizz n'est pas créé.
   *
   *  Après création, l'administrateur est redirigé vers la page d'édition du quizz.
   */
  session_start();
  //TODO: Changer la location
  $index_location = "/github/trivialquizz/admin/index.php";

  //TODO: CHANGER CA
  $_SESSION['is_admin'] = true;
  // On regarde si l'utilisateur est bien un admin
  if(!isset($_SESSION['is_admin']))
  {
    header("Location: ".$index_location);
    exit();
  }

  require_once "../include/liaisonbdd.php";
  require_once "../include/functions.php";

  // On regarde si l'id passé en méthode get est correct
  if(empty($_GET['id']) || !is_numeric($_GET['id']) )
  {
    header("Location: ".$index_location);
    exit();
  }

  $id = $_GET['id'];
  $requete = $bdd -> prepare("SELECT * FROM quiz WHERE qui_id = ?");
  $requete -> execute(array($id));
  $result = $requete -> fetch();

  // Le quizz existe déjà !
  if(!empty($result))
  {
    header("Location: ".$index_location);
    exit();
  }

  // On prépare la liste des noms de tous les quizz pour que l'utilisateur
  // ne rentre pas un nom déjà existant (pas beau).
  $requete = $bdd -> query("SELECT qui_nom FROM quiz");
  $result = $requete -> fetchAll();
  print_r($result);
  exit();
  $listeNoms[] = [];
  $i = 0;
  foreach ($result as $info)
  {
    $listeNoms[$i] = $info["qui_nom"];
    $i++;
  }

?>
<!doctype html>
<html lang="fr">
<head>
  <title>Edition de Quizz</title>
  <?php require_once "../include/header.html"?>
</head>
<body>
  <?php require_once "../include/navbar.php"?>
  <div style="background-color: orange" class="bandeau-principal">Création de Quizz</div>

  <div class="cadre-global">
    <div class="cadre-central">

      <!-- DEBUT : Cadre des options générales -->
      <div>
        <div class="titre1">
          <div>Options générales</div>
        </div>

        <div>
          Les options sont :
          - Le nom
          - Le
        </div>
      </div>
      <!-- FIN : Cadre des options générales -->

    </div>
  </div>

  <?php require_once "../include/script.html"?>
</body>
</html>
