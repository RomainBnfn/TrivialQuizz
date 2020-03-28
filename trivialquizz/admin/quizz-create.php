<?php
  /*  Cette page est accessible via le pannel d'administration. Lors de la création
   * d'un quizz, cette page s'ouvre et l'administrateur peut rentrer les paramètres
   * généraux qui viendront remplir la BDD.
   *  Si cette page n'est pas remplie et envoyer, le quizz n'est pas créé.
   *
   *  Après création, l'administrateur est redirigé vers la page d'édition du quizz.
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
  if(!empty($_POST) && !empty($_POST["nom"])
    && !empty($_POST["desc"])
    && !empty($_POST["theme"])
    && is_numeric($_POST["theme"]))
  {

    //TODO VERIF QUAND MEME TOUT ca

    // On crée le quizz dans la bdd
    $requete = $bdd -> prepare("INSERT INTO quiz (qui_id,
                                                  qui_desc,
                                                  th_id,
                                                  qui_nom)
                                                  VALUES ( 0 , ? , ? , ?)");
    $requete -> execute(array($_POST["desc"],
                              $_POST["theme"],
                              $_POST["nom"]));
    // On redirige l'utilisateur vers la page d'edit

    $requete = $bdd -> query("SELECT MAX(qui_id) FROM quiz");
    $result = $requete -> fetch();
    header("Location: quizz-edit.php?id=".$result[0]);
    exit();
  }

  // On prépare la liste des noms de tous les quizz pour que l'utilisateur
  // ne rentre pas un nom déjà existant (pas beau).
  $listeNoms = getAllQuizzNames($bdd);

//-----------------------------------------

  $_THEMES = getAllThemesInfos($bdd);
  if(empty($_THEMES))
  {
    header("Location: index.php");
    exit();
  }

//-----------------------------------------
?>

<!doctype html>
<html lang="fr">
<head>
  <title>Création de Quizz</title>
  <?php require_once "../include/header.html"?>
</head>
<body>
  <?php require_once "../include/navbar.php"?>

  <div style="background-color: orange" class="bandeau-principal">Création de Quizz</div>

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
              <div id="errorGeneral_Nom" style="color: red; visibility: hidden;"> Ce nom est déjà utilisé !</div>
              <label name="nom">Nom : </label>
              <input id="formGeneral_Nom" type="text" name="nom" required/>
            </div> <br/>

            <div>
              <div id="errorGeneral_Desc"></div>
              <label name="desc">Description : </label>
              <textarea id="formGeneral_Desc" type="text" name="desc" rows="5" draggable="false" required ></textarea>
            </div> <br/>

            <div>
              <label name="theme">Thème :</label>
              <select name="theme" size="1">
                <?php
                foreach ($_THEMES as $_THEME)
                {
                ?>
                  <option value="<?= $_THEME["id"] ?>"><?= $_THEME["nom"] ?></option>
                <?php
                }
                ?>
              </select>
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
