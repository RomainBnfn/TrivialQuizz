<?php
  session_start();
  //TODO: Changer la location
  $index_location = "/trivial/trivialquizz/admin/index.php";

  //TODO: Enlever ça
  $_SESSION['is_admin'] = true;
  // Le visiteur n'est pas un admin.
  if(!isset($_SESSION['is_admin']))
  {
    header("Location: ".$index_location);
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
    header("Location: ".$index_location);
    exit();
  }

?>

<!doctype html>
<html lang="fr">
<head>
  <title>Edition de Theme</title>
  <?php require_once "../include/header.html"?>
</head>
<body>
  <?php require_once "../include/navbar.php"?>

  <!-- TODO: Ajouter une couleur en fct de $couleur -->
  <div class="bandeau-principal fond-bleu">Edition de Thème : <?= $_THEME["nom"] ?></div>

  <div class="cadre-global">
    <div class="cadre-central">

      <?php require_once "include/admin-navbar.php"?>

      <!-- DEBUT : Cadre des options générales -->
      <div>
        <div class="titre1">
          <div>Paramètres Généraux</div>
        </div>

        <div>

          <form id="formGeneral" method="POST" onsubmit="">
            <div>
              <div id="errorGeneral_Nom"></div>
              <label name="nom">Nom : </label>
              <input id="formGeneralNom" type="text" name="nom" value="<?= $_THEME["nom"] ?>" required/>
            </div> <br/>

            <div>
              <div id="errorGeneral_Desc"></div>
              <label name="desc">Description : </label>
              <textarea id="formGeneralDesc" type="text" name="desc" rows="5" draggable="false" required ><?= $_THEME["desc"] ?></textarea>
            </div> <br/>

            <div>
              <div id="errorGeneral_Couleur"></div>
              <label name="couleur">Couleur : </label>
              <input id="formGeneralCouleur" type="text" name="couleur" value="<?= $_THEME["couleur"] ?>" />
            </div>

            <input type="hidden" name="ancien_nom" value="<?= $_THEME["nom"] ?>" />
            <input type="hidden" name="id" value="<?= $_THEME["id"] ?>" />

            <input id="formGeneral_Button"  class="btn btn-success" value="Sauvegarder" type="submit" />
            <span id="infoGeneral_Button" style="color: green; visibility: hidden;"> Les modifications ont été prises en compte !</span>

          </form>
        </div>
      </div>
      <!-- FIN : Cadre des options générales -->

      <br/>

      <!-- DEBUT : Cadre de la liste des Questions -->
      <div>
        <div class="titre1">
          <div>Les Quizz associés</div>
          <div>
            <a>
              <!-- Pour en ajouter plusieurs en même temps -->
              <button type="button" class="btn btn-success">Ajouter</button>
            </a>
          </div>
        </div>

        <div>
          <?php
            $_QUIZZES = getAllQuizzesInfosOfTheme($bdd, $_THEME["id"]);
            if(empty($_QUIZZES))
            {
              echo "Il parrait qu'aucun quizz n'a été associé à ce thème, c'est terrible !";
            }
            else
            {
              foreach ($_QUIZZES as $_QUIZZ)
              {
                ?>
                  <div>
                    <?= $_QUIZZ["nom"] ?>
                    <?php
                    if ($_THEME["id"] != 6) { ?>
                      <button type="button" class="btn btn-error">Dissocier</button>;
                    <?php } ?>
                  </div>
                <?php
              }
            }
           ?>
        </div>
      </div>
      <!-- FIN : Cadre de la liste des Questions -->

    </div>
  </div>

  <?php require_once "../include/script.html"?>
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
              $("#infoGeneral_Button").css("visibility", "visible");
              setTimeout(() => {
                $("#infoGeneral_Button").css("visibility", "collapse");
              }, 5000);
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
    });
  </script>
</body>
</html>
