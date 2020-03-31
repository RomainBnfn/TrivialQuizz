<?php
  session_start();
  //TODO: Changer la location
  $index_location = "/trivial/trivialquizz/admin/index.php";

  //TODO: Changer àa
  $_SESSION["is_admin"] = true;
  if(!isset($_SESSION['is_admin']))
  {
    header("Location: ".$index_location);
    exit();
  }

  require_once "../include/liaisonbdd.php";
  require_once "../include/functions.php";

  if(empty($_GET['id']) || !is_numeric($_GET['id']) )
  {
    header("Location: ".$index_location);
    exit();
  }

  $_QUIZZ = tryLoadQuizz($bdd, $_GET["id"]);
  if(empty($_QUIZZ))
  {
    header("Location: ".$index_location);
    exit();
  }

  $_THEME = tryLoadTheme($bdd, $_QUIZZ["id_theme"]);
  if(empty($_THEME))
  {
    header("Location: ".$index_location);
    exit();
  }

  $_QUESTIONS = tryLoadQuizzQuestion($bdd, $_QUIZZ["id"]);
?>
<!doctype html>
<html lang="fr">
<head>
  <title>Edition de Quizz</title>
  <link rel="stylesheet" href="../css/modal.css" />
  <link rel="stylesheet" href="../css/jumbotron-custom.css" />
  <?php require_once "../include/header.html"?>
</head>
<body>
  <?php require_once "../include/navbar.php"?>
  <!-- TODO: Ajouter une couleur en fct de $couleur -->
  <div id="titreGeneral" class="bandeau-principal fond-bleu">Edition de Quizz : <?= $_QUIZZ["nom"] ?></div>

  <div class="cadre-global">
    <div class="cadre-central">

      <?php require_once "include/admin-navbar.php"?>

      <!-- DEBUT : Cadre des options générales -->
      <div>
        <div class="titre1 jumbotron jumbotron-fluid" style="padding-top: 0; padding-bottom: 0; background-color: white;">
          <h1>Edition générale</h1>
          <!-- Quand on appuie sur le bouton, on envoie une requête -->
          <button id="boutonSuppression" type="button" class="btn btn-danger" style="height: 2.5em;">Supprimer le quizz</button>
        </div>
        <div class="row reduced-div" style="justify-content: space-between;">

          <div class="jumbotron jumbotron-vert col-sm-7">
            <!-- A gauche : Changer le nom... -->
            <!-- A Rajouter

            Bouton Radio (Une après l'autre / En même temps) :
            Bouton Radio Temps Fixe / Décroissant avec difficulté
              Temps max:
            A chaque niveau de difficulté : -5% à -10% (Afficher : 1e : 100% (5min) 5e : 60% (3 min))
            Sauvegarder les modifications
            -->
            <div class="titre-shadow"><h3 class="titre3">Paramètre globaux</h3></div>

            <form id="editGeneral" method="POST">

              <div class="form-group">
                <label for="editGeneral_Nom" class="form-label">
                  Nom:
                </label>
                <input id="editGeneral_Nom" type="text" class="form-control" name="nom" placeholder="Entrez le nom de la Question !" value="<?= $_QUIZZ["nom"] ?>" required/>
                <div id="errorGeneral_Nom" class="invalid-feedback">
                  Ce nom est déjà utilisé !
                </div>
              </div>

              <div class="form-group">
                <label for="editGeneral_Desc" class="form-label">
                  Description:
                </label>
                <textarea id="editGeneral_Desc" type="text" class="form-control" name="desc" placeholder="Entrez le nom de la Question !" required><?= $_QUIZZ["desc"] ?></textarea>
              </div>

              <div class="form-group">
                <label for="editGeneral_Theme" name="id_theme" class="form-label">Thème :</label>
                <select class="form-control" id="editGeneral_Theme" name="id_theme">
                  <?php
                  foreach (getAllThemesInfos($bdd) as $_THEME)
                  {
                  ?>
                    <option value="<?= $_THEME["id"] ?>"><?= $_THEME["nom"] ?></option>
                  <?php
                  }
                  ?>
                </select>
              </div>

              <input type="hidden" name="ancien_nom" value="<?= $_QUIZZ["nom"] ?>" />
              <input type="hidden" name="id" value="<?= $_QUIZZ["id"] ?>" />

              <input id="formGeneral_Button"  class="btn btn-success float-right" value="Sauvegarder" type="submit" />
              <span id="infoGeneral_Button" style="color: green; visibility: hidden;"> Les modifications ont été prises en compte !</span>
            </form>

          </div>

          <div class="jumbotron jumbotron-vert col-sm-4" >
            <h3 class="titre3 titre-shadow">Statistiques</h3>
            <div>
              <!-- A droite : Les statistiques générales: Chargé en dernier
              pour pas prendre trop de temps à la génération -->
              Nb de fois effectué:
              Score moyen:
              Temps moyen:
            </div>
          </div>

        </div>

      </div>
      <!-- FIN : Cadre des options générales -->

<!-- ================================================================================= -->

      <!-- DEBUT : Cadre de la liste des Questions -->
      <div>
        <h2 class="titre1">
          <div>Les questions</div>
          <div>
            <button id="boutonAjouterQuestion" type="button" class="btn btn-success" data-toggle="modal" data-target="#modalCreationQuestion">
              Ajouter
            </button>
            <button id="boutonImporterQuestion" type="button" class="btn btn-info" data-toggle="modal" data-target="#modalImportationQuestion">
              Importer
            </button>
            <button id="boutonViderQuestions" type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalViderQuestions">
              Vider le Quizz
            </button>
          </div>
        </h2>

        <div id="containerQuestions">
          <?php
            if (empty($_QUESTIONS) || $_QUESTIONS == null )
            {
              echo "Ce quizz n'a aucune question ! Pensez à en rajouter !";
            }
            else
            {
              foreach ($_QUESTIONS as $_QUESTION)
              { ?>
                <form id="editQestionN<?= $_QUESTION["id"] ?>" method="" onsubmit="">
                  <div class="jumbotron jumbotron-vert reduced-div row" style="padding-bottom:10px; margin-bottom:15px;">

                      <div class="col-sm-1">
                        <div>UP</div>
                        1
                        <div>DOWN</div>
                      </div>
                      <div class="col-sm-10">

                        <div id="errorQuestion_LibelleN<?= $_QUESTION["id"] ?>" style="color: red; display: none;">Vous ne pouvez pas laisser de libelle vide..</div>
                        <label name="libelle">Libelle : </label>
                        <input id="editQuestion_LibelleN<?= $_QUESTION["id"] ?>" type="text" name="libelle" value="<?= $_QUESTION["lib"] ?>" required/>
                        Réponse : <?= $_QUESTION["rep"]  ?>
                      </div>
                      <div class="col-sm-1 container-fluid">
                        <button class="btn btn-success float-right"><i class="far fa-edit"></i></button>
                        <br/><br/>
                        <button class="btn btn-danger float-right"><i class="far fa-trash-alt"></i></i></button>
                      </div>

                  </div>
                </form>
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
  <?php require_once "modals/question-create.php"?>

  <script>

    var listeNoms = <?=json_encode(getAllQuizzNames($bdd))?>,
        nameQuizz = <?=json_encode($_QUIZZ["nom"])?>;
    listeNoms = listeNoms.filter(function(value, index, arr){ return value != nameQuizz;})

    var idNewQuestion = 1;
    $(document).ready(function(){

      $("#editGeneral").submit((e) => {

        e.preventDefault();

        var form = new FormData(document.getElementById("editGeneral"));
        fetch("ajax/quizz-save-edit.php", {
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
              }, 3000);
            }
          })
        });
      });

      $("#boutonSuppression").click( () => {
        $(this).text("Suppression...");
        fetch("ajax/quizz-delete.php?id=<?= $_QUIZZ["id"] ?>")
        .then(()=>{
          document.location.href="quizz.php";
        });
      });

      $("#editGeneral_Nom").keyup(() => {
        $("#titreGeneral").text("Edition de Quizz : "+ $("#editGeneral_Nom").val());
        if( listeNoms.includes( $("#editGeneral_Nom").val() ))
        {
          $("#errorGeneral_Nom").css("visibility", "visible");
        } else {
          $("#errorGeneral_Nom").css("visibility", "collapse");
        }
      });

    });
  </script>
</body>
</html>
