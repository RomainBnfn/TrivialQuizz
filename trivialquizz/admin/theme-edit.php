<?php
  session_start();
  require_once "../include/index_location.php";

  // Le visiteur n'est pas un admin.
  if(!isset($_SESSION['is_admin']))
  {
    header("Location: ".$index_location."/index.php");
    exit();
  }

  require_once "../include/liaisonbdd.php";
  require_once "../include/functions.php";

  // Si le id en GET n'existe pas ou n'est pas un numbre.
  if(empty($_GET['id']) || !is_numeric($_GET['id']) )
  {
    header("Location: ".$index_location);
    exit();
  }
  $id = $_GET['id'];

  $_THEME = tryLoadTheme($bdd, $id);
  if(empty($_THEME))
  {
    header("Location: $index_location/index.php");
    exit();
  }

?>

<!doctype html>
<html lang="fr">
<head>
  <title>Edition de Theme</title>
  <?php require_once "../include/header.php"?>
  <link rel="stylesheet" href="../css/jumbotron-custom.css" />
  <link rel="stylesheet" href="../css/modal.css" />
  <link rel="stylesheet" href="https://unpkg.com/huebee@1/dist/huebee.min.css">
</head>
<body>
  <?php require_once "../include/navbar.php"?>

  <!-- TODO: Ajouter une couleur en fct de $couleur -->
  <div class="bandeau-principal fond-bleu">
    Edition de Thème : <?= $_THEME["nom"] ?>
  </div>

  <div class="cadre-global">
    <div class="cadre-central">

      <a class="blue-text" href="theme.php">
        <i class="fas fa-arrow-left" style="height: 2.5em;"></i>
        Retour
      <a/>

      <!-- DEBUT : Cadre des options générales -->

      <div class="titre1 titre-shadow">
        <h1>Général</h1>
        <?php if($_THEME["is_Principal"] == 0){?>
          <!-- Quand on appuie sur le bouton, on envoie une requête -->
          <button id="boutonSuppression" type="button" class="btn btn-danger button-open-modal">Supprimer le quizz</button>
        <?php }?>
      </div>
      <article class="container">
        <div class="row reduced-div" style="justify-content: space-between;">

          <div class="jumbotron jumbotron-vert col-sm-12">
            <!-- A gauche : Changer le nom... -->
            <!-- A Rajouter

            Bouton Radio (Une après l'autre / En même temps) :
            Bouton Radio Temps Fixe / Décroissant avec difficulté
              Temps max:
            A chaque niveau de difficulté : -5% à -10% (Afficher : 1e : 100% (5min) 5e : 60% (3 min))
            Sauvegarder les modifications
            -->
            <div class="titre-shadow">
              <h3 class="titre3">Édition globale</h3>
            </div>

            <form id="formGeneral" method="POST">

              <div class="form-group">
                <label for="formGeneralNom" class="form-label">
                  Nom:
                </label>
                <input id="formGeneralNom" type="text" class="input-dark col form-control" name="nom" value="<?= $_THEME["nom"] ?>" placeholder="Entrez le nom de votre Thème !" value="<?= $_QUIZZ["nom"] ?>" required/>
                <div id="errorGeneral_Nom" class="invalid-feedback">
                  Ce nom est déjà utilisé !
                </div>
              </div>

              <div class="form-group">
                <label for="editGeneral_Desc" class="form-label">
                  Description:
                </label>
                <textarea id="editGeneral_Desc" type="text" class="input-dark col form-control" name="desc" rows="5" placeholder="Entrez la description de votre Thème !" required><?= $_THEME["desc"] ?></textarea>
              </div>

              <div class="form-group">
                <label for="editGeneral_Nom" class="form-label">
                  Couleur:
                </label>
                <input id="couleur" type="text" class="color-input col form-control" name="couleur" style="background-color: <?= $_THEME['couleur'] ?>80" value="<?= $_THEME['couleur'] ?>" placeholder="Cliquez pour choisir la couleur !" autocomplete="off" data-huebee='{ "notation": "hex" }' required/>
              </div>

              <input type="hidden" name="ancien_nom" value="<?= $_THEME["nom"] ?>" />
              <input type="hidden" name="id" value="<?= $_THEME["id"] ?>" />

              <input id="formGeneral_Button"  class="btn btn-success float-right" value="Sauvegarder" type="submit" />
              <span id="succedGeneral_Message" style="color: #55FF55; visibility: hidden;"> Les modifications ont été prises en compte !</span>
            </form>

          </div>

        </div>

      </article>
    </div>
  </div>
  <?php require_once "../include/footer.html" ?>
  <?php require_once "../include/script.html"?>
  <script src="https://unpkg.com/huebee@1/dist/huebee.pkgd.min.js">
    var hueb = new Huebee( '.color-input', {
      // options
      notation: 'hex',
      saturations: 2,
    });
  </script>
  <script>
    $(function(){
      $("#formGeneral").submit((e) => {

        e.preventDefault();

        var form = new FormData(document.getElementById("formGeneral"));
        fetch("ajax/theme-save-edit.php", {
          method: "POST",
          body: form
        })
        .then((response) => {
          response.text()
          .then((resp) => {
            if(resp=="ok"){
              $("#succedGeneral_Message").css("visibility", "visible");
              setTimeout(() => {
                $("#succedGeneral_Message").css("visibility", "collapse");
              }, 3000);
            }
          })
        });
      });

      $("#formGeneralNom").on({
        blur : function(){
          if ($(this).val() == "")
          {
            $(this).css({
              borderColor : 'red'
            });
          }
        },

        keyup : function(){
          if ($(this).val() == "")
          {
            $(this).css({
              borderColor : 'red'
            });
          }
          else
          {
            $(this).css({
              borderColor : 'grey'
            });
          }
        }
      });

      $("#boutonSuppression").click(()=>{
        fetch("ajax/theme-delete.php?id=<?= $_THEME["id"] ?>")
          .then((response) => {
            document.location.href="theme.php";
          })
      });
    });
  </script>
</body>
</html>
