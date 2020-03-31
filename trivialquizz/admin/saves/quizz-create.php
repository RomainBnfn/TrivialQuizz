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
    $result = $requete -> execute(array($_POST["desc"],
                              $_POST["theme"],
                              $_POST["nom"]));

    // On redirige l'utilisateur vers la page d'edit
    $id = $bdd->lastInsertId();
    header("Location: quizz-edit.php?id=". $id);
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

            <div class="form-group row">
              <label for="editGeneral_Nom" class="col-sm-2 col-form-label">
                Nom:
              </label>
              <input id="formGeneral_Nom" type="text" class="col form-control" name="nom" placeholder="Entrez le nom de votre Quizz !" required/>
              <div id="errorGeneral_Nom" class="invalid-feedback">
                Ce nom est déjà utilisé !
              </div>
            </div>

            <div class="form-group row">
              <label for="formGeneral_Desc" class="col-sm-2 col-form-label">
                Description:
              </label>
              <textarea id="formGeneral_Desc" type="text" class="col form-control" name="desc" rows="5" placeholder="Entrez la description de votre Quizz !" required></textarea>
            </div>

            <div class="form-group row">
              <label for="formGeneral_Theme" name="theme" class="col-sm-2 col-form-label">Thème :</label>
              <select class="col form-control" id="formGeneral_Theme" name="theme">
                <?php
                foreach ($_THEMES as $_THEME)
                {
                ?>
                  <option value="<?= $_THEME["id"] ?>"><?= $_THEME["nom"] ?></option>
                <?php
                }
                ?>
              </select>
            </div>

            <input class="btn btn-success float-right" value="Créer le Quizz !" type="submit" />
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
