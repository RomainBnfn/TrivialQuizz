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
  if(!empty($_POST) && !empty($_POST["nom"]) && !empty($_POST["desc"]) && !empty($_POST["couleur"]) && is_numeric($_POST["is_Principal"]))
  {
    // On crée le quizz dans la bdd
    $requete = $bdd -> prepare("INSERT INTO theme (th_id, th_nom, th_couleur, th_description, th_is_principal) VALUES ( 0 , ? , ? , ? , ?)");
    $requete -> execute(array($_POST["nom"], $_POST["couleur"], $_POST["desc"],  $_POST["is_Principal"]));
    // On redirige l'utilisateur vers la page d'edit
    header("Location: index.php");
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
