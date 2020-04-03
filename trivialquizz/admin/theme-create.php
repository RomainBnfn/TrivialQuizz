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
    //TODO VERIF QUAND MEME TOUT ca

    // On crée le quizz dans la bdd
    $requete = $bdd -> prepare("INSERT INTO theme (th_id,
                                                  th_nom,
                                                  th_couleur,
                                                  th_is_principal,
                                                  th_description)
                                                  VALUES ( 0 , ? , ? , 0 , ?)");
    $requete -> execute(array($_POST["nom"],
                              $_POST["couleur"],
                              $_POST["desc"]));

    // On redirige l'utilisateur vers la page d'edit
    $id = $bdd->lastInsertId();
    header("Location: theme-edit.php?id=".$id);
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

            <div class="form-group row">
              <label for="editGeneral_Nom" class="col-sm-2 col-form-label">
                Nom:
              </label>
              <input id="formGeneral_Nom" type="text" class="col form-control" name="nom" placeholder="Entrez le nom de votre Thème !" required/>
              <div id="errorGeneral_Nom" class="invalid-feedback">
                Ce nom est déjà utilisé !
              </div>
            </div>

            <div class="form-group row">
              <label for="formGeneral_Desc" class="col-sm-2 col-form-label">
                Description:
              </label>
              <textarea id="formGeneral_Desc" type="text" class="col form-control" name="desc" rows="5" placeholder="Entrez la description de votre Thème !" required></textarea>
            </div>

            <div>
              <div id="errorGeneral_Couleur"></div>
              <label name="couleur">Couleur : </label>
              <input id="formGeneral_Nom" type="text" name="couleur" required/>
            </div> <br/>

            <div class="card bg-color">
              <div class="card-body text-center d-flex justify-content-center align-items-center flex-column">
                <p>My background color will be changed</p>
                <button id="color-picker-3" class="btn">Color Picker</button>
              </div>
            </div>

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
  $(document).ready(function(){
    const pickr3 = new Pickr({
    el: '#color-picker-3',
    useAsButton: true,
    default: "303030",
    components: {
      preview: true,
      opacity: true,
      hue: true,

      interaction: {
        hex: true,
        rgba: true,
        hsla: true,
        hsva: true,
        cmyk: true,
        input: true,
        clear: true,
        save: true
      }
    },

    onChange(hsva, instance) {
      $('.bg-color').css('background-color', hsva.toRGBA().toString());
    }
  });
});
</script>
</body>
</html>
