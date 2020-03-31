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
        <h2 class="titre1">
          <div>Paramètres généraux</div>
          <div>
            <!-- Quand on appuie sur le bouton, on envoie une requête -->
            <button id="boutonSuppression" type="button" class="btn btn-danger">Supprimer le quizz</button>
          </div>
        </h2>
        <div class="container">
          <div>


            <!-- A gauche : Changer le nom... -->
            <!-- A Rajouter

            Bouton Radio (Une après l'autre / En même temps) :
            Bouton Radio Temps Fixe / Décroissant avec difficulté
              Temps max:
            A chaque niveau de difficulté : -5% à -10% (Afficher : 1e : 100% (5min) 5e : 60% (3 min))
            Sauvegarder les modifications
          -->
            <form id="editGeneral" method="POST">

              <div class="form-group row">
                <label for="editGeneral_Nom" class="col-sm-2 col-form-label">
                  Nom:
                </label>
                <input id="editGeneral_Nom" type="text" class="col form-control" name="nom" placeholder="Entrez le nom de la Question !" value="<?= $_QUIZZ["nom"] ?>" required/>
                <div id="errorGeneral_Nom" class="invalid-feedback">
                  Ce nom est déjà utilisé !
                </div>
              </div>

              <div class="form-group row">
                <label for="editGeneral_Desc" class="col-sm-2 col-form-label">
                  Description:
                </label>
                <textarea id="editGeneral_Desc" type="text" class="col form-control" name="desc" placeholder="Entrez le nom de la Question !" required><?= $_QUIZZ["desc"] ?></textarea>
              </div>

              <div>
                <label name="id_theme">Thème :</label>
                <select name="id_theme" size="1">
                  <?php
                  foreach (getAllThemesInfos($bdd) as $_THEME)
                  {
                  ?>
                    <option value="<?= $_THEME["id"] ?>" <?php if($_THEME["id"] == $_QUIZZ["id_theme"]) echo "selected"; ?>><?= $_THEME["nom"] ?></option>
                  <?php
                  }
                  ?>
                </select>
              </div> <br/>

              <input type="hidden" name="ancien_nom" value="<?= $_QUIZZ["nom"] ?>" />
              <input type="hidden" name="id" value="<?= $_QUIZZ["id"] ?>" />

              <input id="formGeneral_Button"  class="btn btn-success" value="Sauvegarder" type="submit" />
              <span id="infoGeneral_Button" style="color: green; visibility: hidden;"> Les modifications ont été prises en compte !</span>
            </form>

          </div>
          <div>
            <!-- A droite : Les statistiques générales: Chargé en dernier
            pour pas prendre trop de temps à la génération -->
            Nb de fois effectué:
            Score moyen:
            Temps moyen:
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
            $_QUESTIONS = tryLoadQuizzQuestion($bdd, $_QUIZZ["id"]);
            if (empty($_QUESTIONS) || $_QUESTIONS == null )
            {
              echo "Ce quizz n'a aucune question ! Pensez à en rajouter !";
            }
            else
            {
              foreach ($_QUESTIONS as $_QUESTION)
              { ?>
                <form id="editQestionN<?= $_QUESTION["id"] ?>" method="" onsubmit="">
                  <div>
                    <div id="errorQuestion_LibelleN<?= $_QUESTION["id"] ?>" style="color: red; visibility: hidden;">Vous ne pouvez pas laisser de libelle vide..</div>
                    <label name="libelle">Libelle : </label>
                    <input id="editQuestion_LibelleN<?= $_QUESTION["id"] ?>" type="text" name="libelle" value="<?= $_QUESTION["lib"] ?>" required/>
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

  <!-- Les modals (Pop-up)-->
  <div class="modal fade" id="modalCreationQuestion" tabindex="-1" role="dialog" aria-labelledby="modalCreationQuestion" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">

        <div class="green-modal-header modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Création de Question</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="creationQuestion" method="POST">

          <div class="modal-body" style="padding-bottom: 0 !important;">

            <div class="form-group row">
              <label for="typeQuestion" class="col-sm-3 col-form-label">Type de la Question :</label>

              <div class="form-check col-sm-3 custom-radio">
                <input id="choix_repLibre" class="form-check-input" type="radio" name="typeQuestion" value="repLibre" checked>
                <label class="form-check-label" for="choix_repLibre">
                  Réponse Libre
                </label>
              </div>

              <div class="form-check col-sm-4 custom-radio">
                <input id="choix_QCM" class="form-check-input" type="radio" name="typeQuestion" value="QCM"/>
                <label class="form-check-label" for="choix_QCM">
                  QCM
                </label>
              </div>
            </div>

            <div class="form-group">
              <label for="exampleInputEmail1">
                <i class="far fa-question-circle" style="color: #339af0;"></i>
                Intitulé de la question :
              </label>
              <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Entrez l'intitulé de la Question !" required/>
            </div>

            <hr/> <!-- ======================================================= -->

            <div id="reponse_TypeLibre" class="form-group">
              <label for="exampleInputEmail1">
                <i class="far fa-check-circle" style="color: #51cf66;"></i>
                Réponse correcte :
              </label>
              <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Entrez cette fameuse réponse !" required/>
              <small id="emailHelp" class="form-text text-muted">Cette réponse devra être indiquée à la lettre près.</small>
            </div>

            <div id="reponse_TypeQCM" class="form-group">
              <label>
                <i class="fas fa-th-list" style="color: #ff922b;"></i>
                Liste des réponse :
                <br/>
                <i>Listez toutes les réponses qui seront proposées, et cochez celles qui sont justes.</i>
              </label>

              <ul class="list-group list-group-flush">

                <li class="list-group-item" style="padding-bottom: 0 !important;">

                    <div class="row">
                      <div class="col-sm-1 form-group" style="text-align: center; vertical-align: middle;">
                        <input class="form-check-input" type="checkbox" value="" id="defaultCheck1"/>
                      </div>
                      <input class="col-sm-10 form-control" type="text"  id="inputAddress" placeholder="La bonne réponse de la question libre." required>
                      <div class="col-sm-1 form-group">
                        <button class="btn btn-danger" type="button">
                            <i class="fas fa-trash-alt" style="color: #ffffff;"></i>
                        </button>
                      </div>

                    </div>
                </li>
                <li class="list-group-item" style="padding-bottom: 0 !important;">

                    <div class="row">
                      <div class="col-sm-1 form-group" style="text-align: center; vertical-align: middle;">
                        <input class="form-check-input" type="checkbox" value="" id="defaultCheck1"/>
                      </div>
                      <input class="col-sm-10 form-control" type="text"  id="inputAddress" placeholder="La bonne réponse de la question libre." required>
                      <div class="col-sm-1 form-group">
                        <button class="btn btn-danger" type="button">
                            <i class="fas fa-trash-alt" style="color: #ffffff;"></i>
                        </button>
                      </div>

                    </div>

                </li>

              </ul>
            </div>

          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
            <input id="formCreation_Button"  class="btn btn-success" value="Ajouter" type="submit" />
          </div>

        </form>

      </div>
    </div>
  </div>

  <?php require_once "../include/script.html"?>
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
              }, 5000);
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

      //-- Modal --
      var _save_htmlLibre = $("#reponse_TypeLibre").html(),
          htmlLibre = _save_htmlLibre;
          _save_htmlQCM = $("#reponse_TypeQCM").html(),
          htmlQCM = _save_htmlQCM;
      $("#reponse_TypeQCM").html("");

      $("#choix_repLibre").click(()=>{
        htmlQCM = $("#reponse_TypeQCM").html();
        $("#reponse_TypeQCM").html("");
        $("#reponse_TypeLibre").html(htmlLibre);
      });

      $("#choix_QCM").click(()=>{
        htmlLibre = $("#reponse_TypeLibre").html();
        $("#reponse_TypeLibre").html("");
        console.log(htmlQCM);
        $("#reponse_TypeQCM").html(htmlQCM);
      });
    });
  </script>
</body>
</html>
