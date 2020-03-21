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
  $index_location = "/trivial/trivialquizz/admin/index.php";

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

  // Si la page est rechargée après avoir envoyé le formulaire
  if(!empty($_POST) && !empty($_POST["nom"]) && !empty($_POST["desc"]) && !empty($_POST["theme"]) && is_numeric($_POST["theme"]))
  {
    // On crée le quizz dans la bdd
    $requete = $bdd -> prepare("INSERT INTO quiz (qui_id, qui_desc, th_id, qui_nom) VALUES ( ? , ? , ? , ?)");
    $requete -> execute(array($_POST["id"], $_POST["desc"], $_POST["theme"], $_POST["nom"]));
    // On redirige l'utilisateur vers la page d'edit
    header("Location: quizz-edit.php");
    exit();
  }

  // On regarde si l'id passé en méthode get est correct
  if(empty($_GET['id']) || !is_numeric($_GET['id']) )
  {
    header("Location: ".$index_location);
    exit();
  }

  $id = $_GET['id'];

  // Le quizz existe déjà !
  if(existQuizz($bdd, $id))
  {
    header("Location: ".$index_location);
    exit();
  }

  // On prépare la liste des noms de tous les quizz pour que l'utilisateur
  // ne rentre pas un nom déjà existant (pas beau).
  $listeNoms = getAllQuizzNames($bdd);

//-----------------------------------------

  // On récupère la liste des thèmes
  $requete = $bdd -> query("SELECT th_id, th_nom FROM theme");
  $result = $requete -> fetchAll();

  // Aucun thème n'existe !
  if(empty($result))
  {
    header("Location: ".$index_location);
    exit();
  }

  $listeThemes[] = [];
  $i = 0;
  foreach ($result as $info)
  {
    $listeThemes[$i] = array($info["th_id"], $info["th_nom"]);
    $i++;
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

      <!-- DEBUT : Cadre des options générales -->
      <div>
        <div class="titre1">
          <div>Options générales</div>
        </div>

        <div>
          <form id="formGeneral" method="POST" onsubmit="">
            <div>
              <div id="formGeneralNom_error"></div>
              <label name="nom">Nom : </label>
              <input id="formGeneralNom" type="text" name="nom" required/>
              <div class="nom_error" style="display: none; color: 'red';">Ce nom de Quiz existe déjà...</div>
            </div> <br/>

            <div>
              <div id="formGeneralDesc_error"></div>
              <label name="desc">Description : </label>
              <textarea id="formGeneralDesc" type="text" name="desc" rows="5" draggable="false" required ></textarea>
            </div> <br/>

            <div>
              <label name="theme">Thème :</label>
              <select name="theme" size="1">
                <?php
                foreach ($listeThemes as $themeInfos)
                {
                  // themeInfos[0] : ID
                  // themeInfos[1] : NOM

                  // PS : La liste a au moins un élément car sinon l'utilisateur
                  // aurait été redirigé.
                ?>
                  <option value="<?= $themeInfos[0] ?>"><?= $themeInfos[1] ?></option>
                <?php
                }
                ?>
              </select>
            </div> <br/>
            <input type="hidden" value="<?= $id ?>" />
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
