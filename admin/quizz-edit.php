<?php
  session_start();

  require_once "../include/index_location.php";

  if($_SESSION == null || !isset($_SESSION['is_admin']))
  {
    header("Location: $index_location/index.php");
    exit();
  }

  require_once "../include/liaisonbdd.php";
  require_once "../include/functions.php";

  if(empty($_GET['id']) || !is_numeric($_GET['id']) )
  {
    header("Location: $index_location/index.php");
    exit();
  }

  $_QUIZZ = tryLoadQuizz($bdd, $_GET["id"]);
  if(empty($_QUIZZ))
  {
    header("Location: $index_location/index.php");
    exit();
  }

  $_THEME = tryLoadTheme($bdd, $_QUIZZ["id_theme"]);
  if(empty($_THEME))
  {
    header("Location: $index_location/index.php");
    exit();
  }

  $_QUESTIONS = tryLoadQuizzQuestion($bdd, $_QUIZZ["id"]);
  $_NBASSOCIATION = getAllNbAssociationQuestion($bdd, $_QUIZZ["id"]);
?>
<!doctype html>
<html lang="fr">
<head>
  <title>Edition de Quizz</title>
  <link rel="stylesheet" href="../css/modal.css" />
  <link rel="stylesheet" href="../css/jumbotron-custom.css" />
  <?php require_once "../include/header.php"?>
</head>
<body>
  <?php require_once "../include/navbar.php"?>
  <!-- TODO: Ajouter une couleur en fct de $couleur -->
  <div id="titreGeneral" class="bandeau-principal fond-bleu">
    Edition de Quizz : <?= $_QUIZZ["nom"] ?>
  </div>

  <div class="cadre-global">
    <div class="cadre-central">

      <!-- DEBUT : Cadre des options générales -->

      <a class="blue-text" href="quizz.php">
        <i class="fas fa-arrow-left" style="height: 2.5em;"></i>
        Retour
      <a/>

      <div class="titre1 titre-shadow">
        <div>
          <h1>Général</h1>
        </div>
        <!-- Quand on appuie sur le bouton, on envoie une requête -->
        <button id="boutonSuppression" type="button" class="btn btn-danger" style="height: 2.5em;">Supprimer le quizz</button>
      </div>

      <article class="container">
        <div class="row " style="justify-content: space-between;">

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
                <label for="editGeneral_Nom" class="main-label form-label" style="color: #AAFFFF !important">
                  <i class="fas fa-signature"></i>
                  Nom:
                </label>
                <input id="editGeneral_Nom" type="text" class="input-dark form-control" maxlength="50" name="nom" placeholder="Entrez le nom de la Question !" value="<?= $_QUIZZ["nom"] ?>" autocomplete="off" required/>
                <div id="messageNomQuizzError" class="invalid-feedback">
                    Le nom de quizz est invalide ou déjà utilisé !
                </div>
              </div>

              <div class="form-group">
                <label for="editGeneral_Desc" class="main-label form-label" style="color: #AAFFFF !important">
                  <i class="fas fa-file-alt"></i>
                  Description:
                </label>
                <textarea id="editGeneral_Desc" type="text" class="input-dark form-control" maxlength="300" name="desc" rows="3" placeholder="Entrez le nom de la Question !" required><?= $_QUIZZ["desc"] ?></textarea>
              </div>

              <div class="form-group">
                <label for="editGeneral_Theme" name="id_theme" class="main-label form-label" style="color: #AAFFAA !important">
                  <i class="fas fa-certificate"></i>
                  Thème:
                </label>
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

              <hr/>

              <div class="form-group">
                <label for="editGeneral_Nom" class="main-label form-label" style="color: #E3C6D5 !important">
                  <i class="fas fa-hourglass-half"></i>
                  Temps de base:
                </label>
                <input id="editGeneral_Temps" type="number" class="input-dark form-control" min="60" name="temps" placeholder="Entrez le temps de base (en seconde)."
                value="<?= $_QUIZZ["temps"] ?>" oninput="temps_changed();" autocomplete="off" required/>
                <small class="form-text text-muted" style="color: white !important;">
                  Le temps de base est celui du niveau de difficulté le plus bas. (Et diminue pour les autres)
                </small>
              </div>

              <div class="form-group">
                <label for="editGeneral_Malus" class="main-label form-label" style="width: 100%; color: #F1B8B8 !important">
                  <i class="fas fa-exclamation-circle"></i>
                  Malus Temps:
                </label>
                <small class="form-text text-muted" style="color: white !important;">
                  Le malus est un % de temps enlevé à chaque niveau de difficulté.
                </small>
                <br/>
                <div class="row reduced-row">
                  <div class="col-sm-1">-0%</div>
                  <div class="col-sm-10 text-center">
                    <input id="editGeneral_Malus" name="malus" type="range" class="custom-range"
                    value="<?= $_QUIZZ["malus"] ?>" min="0" max="15" step="1" oninput="temps_changed();">
                  </div>
                  <div class="col-sm-1">-15%</div>
                </div>
                <div class="text-center">
                  <span class="badge badge-pill badge-info">
                    <span id="amountMalus" style="font-size:13px;">
                      -<?= $_QUIZZ["malus"] ?>%
                    </span>
                  </span>
                </div>

                <div>
                  <label>Exemples :</label>
                  <div>
                    Niveau Facile :
                    <span id="tempsFacile">
                      <?= timeToString($_QUIZZ["temps"]) ?>
                    </span>
                  </div>

                  <div>
                    Niveau Extrême :
                    <span id="tempsDifficile">
                      <?= timeToString((int) ( $_QUIZZ["temps"] * (1 - $_QUIZZ["malus"]/100) )) ?>
                    </span>
                  </div>
                </div>
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
              <?php $_STATS = getStatistique($bdd, $_QUIZZ["id"]);?>
              <!-- A droite : Les statistiques générales: Chargé en dernier
              pour pas prendre trop de temps à la génération -->
              <div>
                <label class="main-label form-label" style="width: 100%; color: #AAFFFF !important">
                  <i class="fab fa-flickr"></i>
                  Nombre d'utilisation:
                </label>
                <span><?= (int) $_STATS["nb"] ?></span>
              </div>

              <hr/>

              <div>
                <label class="main-label form-label" style="width: 100%; color: #AAFFAA !important">
                  <i class="fas fa-trophy"></i>
                  Score moyen:
                </label>
                <span><?= (int) $_STATS["score"] ?></span>
              </div>

              <hr/>

              <div>
                <label class="main-label form-label" style="width: 100%; color: #E3E371 !important">
                  <i class="fas fa-history"></i>
                  Temps moyen:
                </label>
                <span><?= timeToString((int) $_STATS["temps"]) ?></span>
              </div>

              <hr/>

              <div>
                <label class="main-label form-label" style="width: 100%; color: #F1B8B8 !important">
                  <i class="fas fa-fire"></i>
                  Difficulte moyenne:
                </label>
                <span><?= (int) $_STATS["difficulte"] ?></span>
              </div>
            </div>
          </div>
      </article>
      <!-- FIN : Cadre des options générales -->

      <!-- ================================================================================= -->
      </article>
      <!-- DEBUT : Cadre de la liste des Questions -->

      <div class="titre1 titre-shadow">
        <div>
          <h1 >
            Les questions
          </h1>
        </div>
        <div>
        <!-- Quand on appuie sur le bouton, on envoie une requête -->
          <button type="button" class="btn btn-success button-open-modal" data-toggle="modal" data-target="#modalCreationQuestion">
            Ajouter
          </button>
          <button type="button" class="btn btn-info button-open-modal" data-toggle="modal" data-target="#modalImportationQuestion">
            Importer
          </button>
          <?php if(!empty($_QUESTIONS) && count($_QUESTIONS) > 0){ ?>
            <button type="button" class="btn btn-danger button-open-modal" data-toggle="modal" data-target="#modalConfirmationVider">
              Vider le Quizz
            </button>
          <?php } ?>
        </div>
      </div>

      <article class="container">

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
                $queId = $_QUESTION["id"];
                ?>

                <!-- ===========CONTAINER QUESTION =========== -->

                  <div id="containerQuestionN<?= $_QUESTION["id"] ?>" class="jumbotron jumbotron-vert questionContainer" style="order: <?= $_QUESTION['order'] ?>;">
                    <form id="editQuestionN<?= $_QUESTION["id"] ?>" data-idquestion="<?= $_QUESTION["id"]?>" data-idquizz="<?= $_QUIZZ["id"]?>"  class="form-edit-question" method="POST">
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

                            <!-- LIBRE -->
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
                                  <input id="input_repLibreN<?= $_QUESTION["id"]?>" name="reponse" type="text" class="input-dark form-control" name="nom" placeholder="Entrez le nom de votre Quizz !" autocomplete="off" value="<?= $_REPONSE["lib"] ?>" required/>
                                <?php } else{ ?>
                                  <input name="reponseID" type="hidden" value="-1" required/>
                                  <input id="input_repLibreN<?= $_QUESTION["id"]?>" name="reponse" type="text" class="input-dark form-control" name="nom" placeholder="Entrez le nom de votre Quizz !" autocomplete="off" required/>
                                <?php } ?>
                                <small class="form-text text-muted" style="color: white !important;">
                                  Cette réponse devra être indiquée à la lettre près.
                                </small>
                              </div>
                            </div>

                            <!-- QCM -->
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
                                  for($u = 1; $u<=4; $u++){ ?>
                                  <li class="dark-background list-group-item">

                                      <div class="row">
                                        <div class="col-sm-1 form-group" style="text-align: center; vertical-align: middle;">
                                          <?php if($u==1){?>
                                            <i class="fas fa-check-circle" style="color: #51cf66;"></i>
                                          <?php }else{?>
                                            <i class="fas fa-times-circle" style="color: #ff6b6b;"></i>
                                          <?php } ?>
                                        </div>
                                        <input name="reponseID<?= $u ?>" type="hidden"
                                        <?php if($_QUESTION["type"]==1){ ?>value="-1" <?php }else{ $idR = $keys[$u-1]; ?> value="<?= $_QUESTION["reponses"][$idR]["id"] ?>"<?php } ?> required/>

                                        <input class="input-dark col-sm-11 form-control" name="reponseQCM_N<?=$u?>" autocomplete="off" type="text" placeholder="<?php if($u==1){ echo "Entrez la réponse correcte."; }else{ echo "Entrez une mauvaise réponse"; } ?>"
                                        <?php if($_QUESTION["type"]==2){ $idR = $keys[$u-1]; ?> value="<?= $_QUESTION["reponses"][$idR]["lib"] ?>" <?php } ?>required>
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
                        <?php
                          if($_NBASSOCIATION["$queId"] > 1){?>
                            <button class="btn-delier-question btn btn-info float-right" data-idquestion="<?= $_QUESTION["id"]?>" data-idquizz="<?= $_QUIZZ['id']?>" type='button' style="margin-left:5px;">
                              <i class="fas fa-unlink"></i>
                            </button>
                        <?php } ?>
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
  <?php require_once "../include/footer.html" ?>
  <?php require_once "../include/script.html"?>
  <?php require_once "modals/question-create.php"?>
  <?php require_once "modals/question-importer.php"?>
  <?php require_once "modals/confirmation-question-vider.php"?>

<script>

  var listeNoms = <?=json_encode(getAllQuizzNames($bdd))?>,
      nameQuizz = <?=json_encode($_QUIZZ["nom"])?>,
      tableauQuestionOrder = <?=json_encode($tableauQuestionOrder)?>,
      idQuizz = <?= $_QUIZZ["id"] ?>;
  listeNoms = listeNoms.filter(function(value, index, arr){ return value != nameQuizz;});
  <?php
    if(isset($_QUESTIONS) && !empty($_QUESTIONS)){
      foreach ($_QUESTIONS as $_QUESTION) { ?>
        var html_repLibre<?= $_QUESTION["id"] ?> = $("#reponseLibreN<?= $_QUESTION["id"] ?>").html(),
            html_repQCM<?= $_QUESTION["id"] ?> = $("#reponseQCMN<?= $_QUESTION["id"] ?>").html();
            <?php
            if ($_QUESTION["type"] == 1){ ?>
              $("#reponseQCMN<?= $_QUESTION["id"] ?>").html("");
              <?php } else { ?>
              $("#reponseLibreN<?= $_QUESTION["id"] ?>").html("");
            <?php }
       }
     } ?>

  $(document).ready(()=>{

    $( ".switchType" ).change(function() {
      var id = $(this).data("idquestion");
      switchType($(this), id);
    });

    $(".form-edit-question").submit((e) => {
      e.preventDefault();
      var id = e.target.dataset.idquestion;
      saveQuestionReponse(id);
    });

  });
</script>
<script type="text/javascript" src="js/quizz-edit.js"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
</body>
</html>
