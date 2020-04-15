<?php
  session_start();
  require_once "../include/index_location.php";

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

      <!-- DEBUT : Cadre des options générales -->
      <div>
        <div class="titre1 titre-shadow">
          <h1>Général</h1>
          <!-- Quand on appuie sur le bouton, on envoie une requête -->
          <button id="boutonSuppression" type="button" class="btn btn-danger" style="height: 2.5em;">Supprimer le quizz</button>
        </div>
        <div class="row reduced-div" style="justify-content: space-between;">

          <div class="jumbotron jumbotron-vert col-lg-7">
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

            <form id="editGeneral" method="POST">

              <div class="form-group">
                <label for="editGeneral_Nom" class="form-label">
                  Nom:
                </label>
                <input id="editGeneral_Nom" type="text" class="input-dark form-control" name="nom" placeholder="Entrez le nom de la Question !" value="<?= $_QUIZZ["nom"] ?>" required/>
                <div id="errorGeneral_Nom" class="invalid-feedback">
                  Ce nom est déjà utilisé !
                </div>
              </div>

              <div class="form-group">
                <label for="editGeneral_Desc" class="form-label">
                  Description:
                </label>
                <textarea id="editGeneral_Desc" type="text" class="input-dark form-control" name="desc" placeholder="Entrez le nom de la Question !" required><?= $_QUIZZ["desc"] ?></textarea>
              </div>

              <div class="form-group">
                <label for="editGeneral_Theme" name="id_theme" class="form-label">Thème :</label>
                <select class="input-dark form-control" id="editGeneral_Theme" name="id_theme">
                  <optgroup label="Thèmes Principaux">
                  <?php
                  $_THEMES = getAllThemesInfos($bdd);
                  foreach ($_THEMES as $_THEME)
                  {
                  ?>
                  <option value="<?= $_THEME["id"] ?>" <?php if($_THEME["id"] == $_QUIZZ["id_theme"]) {echo"selected"; }?>><?= $_THEME["nom"] ?></option>

                  <?php
                    if($_THEME["id"] == 6 && count($_THEMES) > 6){
                      echo "</optgroup><optgroup label='Thèmes personnalisés'>";
                    }
                  }
                  ?>
                  </optgroup>
                </select>
              </div>

              <input type="hidden" name="ancien_nom" value="<?= $_QUIZZ["nom"] ?>" />
              <input type="hidden" name="id" value="<?= $_QUIZZ["id"] ?>" />

              <input id="formGeneral_Button"  class="btn btn-success float-right" value="Sauvegarder" type="submit" />
              <span id="infoGeneral_Button" style="color: #55FF55; visibility: hidden;">
                Les modifications ont été prises en compte !
              </span>
            </form>

          </div>

          <div class="jumbotron jumbotron-vert col-lg-4" >
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
        <div class="titre1 titre-shadow">
          <h1 >Les questions</h1>
          <!-- Quand on appuie sur le bouton, on envoie une requête -->
          <div >
            <button id="boutonAjouterQuestion" type="button" class="btn btn-success button-open-modal" data-toggle="modal" data-target="#modalCreationQuestion">
              Ajouter
            </button>
            <button id="boutonImporterQuestion" type="button" class="btn btn-info button-open-modal" data-toggle="modal" data-target="#modalImportationQuestion">
              Importer
            </button>
            <button id="boutonViderQuestions" type="button" class="btn btn-danger button-open-modal" data-toggle="modal" data-target="#modalConfirmationVider">
              Vider le Quizz
            </button>
          </div>
        </div>


        <div class="containerQuestions" id="containerQuestions">
          <?php
            $tableauQuestionOrder = [];
            $nbQuestions = 0;
            if (empty($_QUESTIONS) || $_QUESTIONS == null )
            {
              echo "Ce quizz n'a aucune question ! Pensez à en rajouter !";
            }
            else
            {
              $nbQuestions = count($_QUESTIONS);
              $i=-1;
              foreach ($_QUESTIONS as $_QUESTION)
              {
                $i++;
                $tableauQuestionOrder[$i] = $_QUESTION["id"];
                ?>

                <!-- ===========CONTAINER QUESTION =========== -->

                  <div id="containerQuestionN<?= $_QUESTION["id"] ?>" class="jumbotron jumbotron-vert questionContainer" style="order: <?= $_QUESTION['order'] ?>;">
                    <form id="editQuestionN<?= $_QUESTION["id"] ?>" data-idquestion="<?= $_QUESTION["id"]?>"  class="form-edit-question" method="POST">
                      <div class="reduced-div row">
                        <input id="editQuestion_IdN<?= $_QUESTION["id"] ?>" name="id" type="hidden" value="<?= $_QUESTION["id"] ?>" required/>

                        <!-- FLECHES -->
                        <div class="col-sm-1 d-flex align-items-center">
                          <div>
                            <div id="questionUpN<?= $_QUESTION["id"] ?>" onclick="moveQuestion(-1, <?= $_QUESTION["id"] ?>, <?= $_QUESTION["order"] ?>); return false;" <?php if( $_QUESTION["order"] <= 1){ echo "style='display: none'";}?>>
                              <i class="fas fa-arrow-up"></i>
                            </div>

                            <span id="badgeQuestionN<?= $_QUESTION["id"] ?>" class="badge badge-info">
                              <?= $_QUESTION["order"] ?>
                            </span>

                            <div id="questionDownN<?= $_QUESTION["id"] ?>"onclick="moveQuestion(1, <?= $_QUESTION["id"] ?>, <?= $_QUESTION["order"] ?>); return false;" <?php if( $_QUESTION["order"] >= $nbQuestions){ echo "style='display: none'";}?>>
                              <i class="fas fa-arrow-down"></i>
                            </div>
                          </div>
                        </div>

                        <div class="col-sm-11">


                          <!-- TYPE DE QUESTION -->
                          <div class="form-group row col">
                            <label for="typeQuestion" class="col-sm-4 col-form-label">Type de la Question:</label>

                          <input id="choix_typeQuestionN<?= $_QUESTION["id"] ?>" data-idquestion="<?= $_QUESTION["id"]?>" class="switchType" name="typeQuestion" type="checkbox" <?php if($_QUESTION["type"] == 1) echo "checked";?> data-toggle="toggle" data-style="ios" data-on="Réponse Libre" data-off="QCM" data-onstyle="success" data-offstyle="info" />

                          </div>


                          <!-- LIBELLE -->
                          <div class="form-group">
                            <label for="libelle" class="col form-label">
                              Libellé:
                            </label>
                            <input id="editQuestion_LibelleN<?= $_QUESTION["id"] ?>" name="libelle" type="text" class="input-dark form-control" name="nom" placeholder="Entrez le nom de votre Quizz !" autocomplete="off" value="<?= $_QUESTION["lib"] ?>" required/>
                          </div>

                          <hr/>


                          <!-- REPONSES -->
                          <div id="reponsesN<?= $_QUESTION["id"] ?>"><!-- REPONSES -->

                            <div id="reponseLibreN<?= $_QUESTION["id"] ?>">

                              <div class="form-group">
                                <label for="nom" class="col">
                                  <i class="far fa-check-circle" style="color: #51cf66;"></i>
                                  Réponse correcte :
                                </label>
                                <?php
                                  if($_QUESTION["type"]==1){
                                    $idRep = array_key_first ($_QUESTION["reponses"]);
                                    $_REPONSE = $_QUESTION["reponses"][$idRep];
                                  ?>
                                  <input name="reponseID" type="hidden" value="<?= $_REPONSE["id"] ?>" required/>
                                  <input name="reponse" type="text" class="input-dark form-control" name="nom" placeholder="Entrez le nom de votre Quizz !" autocomplete="off" value="<?= $_REPONSE["lib"] ?>" required/>
                                <?php } else{ ?>
                                  <input name="reponseID" type="hidden" value="-1" required/>
                                  <input name="reponse" type="text" class="input-dark form-control" name="nom" placeholder="Entrez le nom de votre Quizz !" autocomplete="off" required/>
                                <?php } ?>
                                <small class="form-text text-muted" style="color: white !important;">
                                  Cette réponse devra être indiquée à la lettre près.
                                </small>
                              </div>
                            </div>

                            <div id="reponseQCMN<?= $_QUESTION["id"] ?>">
                              <?php $keys = array_keys($_QUESTION["reponses"]);
                              ?>
                              <label class="col">
                                <i class="fas fa-th-list" style="color: #339af0;"></i>
                                Liste des réponses :
                                <br/>
                                <small class="form-text text-muted" style="color: white !important;">
                                  Listez toutes les réponses qui peuvent être affichées. La première est juste, les autres fausses.
                                </small>
                              </label>

                              <ul class="dark-background list-group col list-group-flush">
                                <?php
                                  for($i = 1; $i<=4; $i++){ ?>
                                  <li class="dark-background list-group-item">

                                      <div class="row">
                                        <div class="col-sm-1 form-group" style="text-align: center; vertical-align: middle;">
                                          <?php if($i==1){?>
                                            <i class="fas fa-check-circle" style="color: #51cf66;"></i>
                                          <?php }else{?>
                                            <i class="fas fa-times-circle" style="color: #ff6b6b;"></i>
                                          <?php } ?>
                                        </div>
                                        <input name="reponseID<?= $i ?>" type="hidden"
                                        <?php if($_QUESTION["type"]==1){ ?>value="-1" <?php }else{ $idR = $keys[$i-1]; ?> value="<?= $_QUESTION["reponses"][$idR]["id"] ?>"<?php } ?> required/>

                                        <input class="input-dark col-sm-11 form-control" name="reponseQCM_N<?=$i?>" type="text" placeholder="<?php if($i==1){ echo "Entrez la réponse correcte."; }else{ echo "Entrez une mauvaise réponse"; } ?>"
                                        <?php if($_QUESTION["type"]==2){ $idR = $keys[$i-1]; ?> value="<?= $_QUESTION["reponses"][$idR]["lib"] ?>" <?php } ?>required>
                                      </div>
                                  </li>
                              <?php } ?>
                              </ul>
                            </div>

                          </div>
                        </div >

                      </div>

                      <div class="col">
                        <button class="btn btn-danger float-right" type='button' onclick="deleteQuestion(<?= $_QUESTION["id"] ?>);" style="margin-left:5px;">
                          <i class="far fa-trash-alt"></i>
                        </button>
                        <button class="btn btn-info float-right" type='button' style="margin-left:5px;">
                          <i class="fas fa-unlink"></i>
                        </button>
                        <button id="editQuestion_BtnN<?= $_QUESTION["id"] ?>" type="submit"  class="btn btn-success float-right" style="margin-left:5px;">
                          <i id="editQuestion_BtnCtnN<?= $_QUESTION["id"] ?>" class="far fa-edit"></i>
                        </button>
                      </div>
                    </form>
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
  <?php require_once "modals/question-create.php"?>
  <?php require_once "modals/confirmation-question-vider.php"?>

<script>

  var listeNoms = <?=json_encode(getAllQuizzNames($bdd))?>,
      nameQuizz = <?=json_encode($_QUIZZ["nom"])?>,
      tableauQuestionOrder = <?=json_encode($tableauQuestionOrder)?>,
      idQuizz = <?= $_QUIZZ["id"] ?>;

  listeNoms = listeNoms.filter(function(value, index, arr){ return value != nameQuizz;});
  <?php
    foreach ($_QUESTIONS as $_QUESTION) { ?>
      var html_repLibre<?= $_QUESTION["id"] ?> = $("#reponseLibreN<?= $_QUESTION["id"] ?>").html(),
          html_repQCM<?= $_QUESTION["id"] ?> = $("#reponseQCMN<?= $_QUESTION["id"] ?>").html();
          <?php
          if ($_QUESTION["type"] == 1){ ?>
            $("#reponseQCMN<?= $_QUESTION["id"] ?>").html("");
            <?php } else { ?>
            $("#reponseLibreN<?= $_QUESTION["id"] ?>").html("");
          <?php }
     } ?>

  $(document).ready(()=>{

    $( ".switchType" ).change(function() {
      var id = $(this).data("idquestion");
      if($(this).prop('checked')) {
        window["html_repQCM"+id] = $("#reponseQCMN"+id).html();
        $("#reponseQCMN"+id).html("");
        $("#reponseLibreN"+id).html(window["html_repLibre"+id]);
      }
      else{
        window["html_repLibre"+id] = $("#reponseLibreN"+id).html();
        $("#reponseLibreN"+id).html("");
        $("#reponseQCMN"+id).html(window["html_repQCM"+id]);
      }
    });

    $(".form-edit-question").submit((e) => {
      alert( "Handler for .submit() called." );
      event.preventDefault();
    });
  });
</script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script type="text/javascript" src="js/quizz-edit.js"></script>
</body>
</html>
