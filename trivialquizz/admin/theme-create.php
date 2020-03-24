<?php
  /*  Cette page est accessible via le pannel d'administration. Lors de la création
   * d'un theme, cette page s'ouvre et l'administrateur peut rentrer les paramètres
   * généraux qui viendront remplir la BDD.
   *  Si cette page n'est pas remplie et envoyer, le thème n'est pas créé.
   *
   *  Après création, l'administrateur est redirigé vers la page d'édition du thème.
   */
  session_start();

  //TODO: Changer ça
  $index_location = "/trivial/trivialquizz/admin/index.php";
  $_SESSION['is_admin'] = true;

  // On regarde si l'utilisateur est bien un admin
  if(!isset($_SESSION['is_admin']))
  {
    header("Location: ".$index_location);
    exit();
  }

  require_once "../include/liaisonbdd.php";
  require_once "../include/functions.php";

  // Si la page est rechargée après avoir envoyé le formulaire
  if(!empty($_POST)
  && !empty($_POST["nom"])
  && !empty($_POST["desc"])
  && !empty($_POST["couleur"])
  )
  {
    // On crée le quizz dans la bdd
    $requete = $bdd -> prepare("INSERT INTO theme (th_id, th_nom, th_couleur, th_is_principal, th_description) VALUES ( 0 , ? , ? , 0 , ?)");
    $requete -> execute(array($_POST["nom"], $_POST["couleur"], $_POST["desc"]));

    // On redirige l'utilisateur vers la page d'edit
    header("Location: index.php");
    exit();
  }

  // On prépare la liste des noms de tous les quizz pour que l'utilisateur
  // ne rentre pas un nom déjà existant (pas beau).
  $listeNoms = getAllThemesNames($bdd);

//-----------------------------------------
?>

<!doctype html>
<html lang="fr">
<head>
  <title>Création de Thème</title>
  <?php require_once "../include/header.html"?>
</head>
<body>
  <?php require_once "../include/navbar.php"?>
  <div style="background-color: pink" class="bandeau-principal">Création de Thème</div>

  <div class="cadre-global">
    <div class="cadre-central">

      <?php require_once "include/admin-navbar.php"?>

      <!-- DEBUT : Cadre des options générales -->
      <div>
        <div class="titre1">
          <div>Options générales</div>
        </div>

        <div>
          <form id="formGeneral" method="POST" onsubmit="">


            <div>
              <div id="errorGeneral_Nom" style="display: none; color: 'red';">Ce nom de Quiz existe déjà...</div>
              <label name="nom">Nom : </label>
              <input id="formGeneral_Nom" type="text" name="nom" required/>
            </div> <br/>


            <div>
              <div id="errorGeneral_Desc"></div>
              <label name="desc">Description : </label>
              <textarea id="formGeneral_Desc" type="text" name="desc" rows="5" draggable="false" required ></textarea>
            </div> <br/>


            <div>
              <div id="errorGeneral_Couleur"></div>
              <label name="couleur">Couleur : </label>
              <input id="formGeneral_Nom" type="text" name="couleur" required/>
            </div> <br/>


            <input type="submit" />


          </form>

        </div>
      </div>
      <!-- FIN : Cadre des options générales -->

    </div>
  </div>

  <?php require_once "../include/script.html"?>
<script>
  <?php //Condition sur le nom : in_array("TESTTT", $listeNoms)?>
</script>
</body>
</html>
