<?php
  session_start();
  //TODO: Changer la location
  $index_location = "/trivial/trivialquizz/admin/quizz.php";

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
        <div class="titre1 titre-shadow">
          <h1>Général</h1>
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
              <span id="infoGeneral_Button" style="color: #55FF55; visibility: hidden;"> Les modifications ont été prises en compte !</span>
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
        <div class="titre1 titre-shadow">
          <h1>Les questions</h1>
          <!-- Quand on appuie sur le bouton, on envoie une requête -->
          <div >
            <button id="boutonAjouterQuestion" type="button" class="btn btn-success button-open-modal" data-toggle="modal" data-target="#modalCreationQuestion">
              Ajouter
            </button>
            <button id="boutonImporterQuestion" type="button" class="btn btn-info button-open-modal" data-toggle="modal" data-target="#modalImportationQuestion">
              Importer
            </button>
            <button id="boutonViderQuestions" type="button" class="btn btn-danger button-open-modal" data-toggle="modal" data-target="#modalViderQuestions">
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
              $nbQuestions = 0;
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
                  <div id="containerQuestionN<?= $_QUESTION["id"] ?>" class="jumbotron jumbotron-vert questionContainer" style="order: <?= $_QUESTION['order'] ?>;">
                    <form id="editQuestionN<?= $_QUESTION["id"] ?>" method="POST" onsubmit="saveQuestionReponse(<?= $_QUESTION["id"] ?>); return false;">
                      <div class="reduced-div row">
                        <input id="editQuestion_IdN<?= $_QUESTION["id"] ?>" name="id" type="hidden" value="<?= $_QUESTION["id"] ?>" required/>

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

                          <div class="form-group">
                            <label for="editGene" name="id_theme" class="col form-label">
                              Type de la Question:
                            </label>
                            <select class="input-dark form-control" id="editGene" name="id_theme">
                            <option value="e">Réponse Libre</option>
                              <option value="t">QCM</option>
                            </select>
                          </div>

                          <div class="form-group">
                            <label for="libelle" class="col form-label">
                              Libellé:
                            </label>
                            <input id="editQuestion_LibelleN<?= $_QUESTION["id"] ?>" name="libelle" type="text" class="input-dark form-control" name="nom" placeholder="Entrez le nom de votre Quizz !" autocomplete="off" value="<?= $_QUESTION["lib"] ?>" required/>
                          </div>
                          <div ><!-- REPONSES -->
                            <?php
                              if($_QUESTION["type"] == 1){
                                $idRep = array_key_first ($_QUESTION["reponses"]);
                                $_REPONSE = $_QUESTION["reponses"][$idRep];
                                ?>
                                <div class="form-group">
                                  <label for="libelle" class="col form-label">
                                    Réponse exacte:
                                  </label>
                                  <input id="editQuestion_ReponseN<?= $_REPONSE["id"] ?>" name="reponse" type="text" class="input-dark form-control" name="nom" placeholder="Entrez le nom de votre Quizz !" autocomplete="off" value="<?= $_REPONSE["lib"] ?>" required/>
                                </div>
                                <?php
                              }
                              else if ($_QUESTION["type"] == 2){
                                ?>
                                Mais c'est un QCM :O
                                <?php
                              }
                            ?>

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
  <?php require_once "modals/question-importer.php"?>

  <script>

    var listeNoms = <?=json_encode(getAllQuizzNames($bdd))?>,
        nameQuizz = <?=json_encode($_QUIZZ["nom"])?>,
        tableauQuestionOrder = <?=json_encode($tableauQuestionOrder)?>,
        idQuizz = <?= $_QUIZZ["id"] ?>;

    listeNoms = listeNoms.filter(function(value, index, arr){ return value != nameQuizz;});

    //return entre 1 et nbQuestions
    function getPos(id)
    {
      for(var i = 0; i < tableauQuestionOrder.length ; i++){
        if(tableauQuestionOrder[i]==id)
          return i+1;
      };
      return null;
    }

    // pos entre 1 et nbQuestions
    function decreasePosition(pos)
    {
      var nbQuestions = tableauQuestionOrder.length;
      var id = 0;
      for(var i = pos-1; i< nbQuestions-1; i++){
        id = tableauQuestionOrder[i+1];
        tableauQuestionOrder[i] = id;
        editFleches(i+1, id);
        editBadge(i+1, id);
      }
      tableauQuestionOrder.pop();
      if(id == 0) id = tableauQuestionOrder[nbQuestions-2];
      editFleches(nbQuestions-1, id);
    }

    function deleteQuestion(id)
    {
      $(() =>{
        fetch("ajax/question-delete.php?id="+id)
        .then((response)=>{
          response.text()
          .then((resp)=>{
            if(resp == "ok"){
              $("#containerQuestionN"+id).remove();
              decreasePosition(getPos(id));
            }
          });
        })

      });
    }

    //pos entre 1 et nbQuestions
    function editFleches(pos, id){
      var flecheUp = document.getElementById("questionUpN"+id),
          flecheDown = document.getElementById("questionDownN"+id),
          nbQuestions = tableauQuestionOrder.length;

      flecheUp.onclick = () => {
        moveQuestion(-1, id, pos);
        return false
      };
      flecheDown.onclick = () => {
        moveQuestion(1, id, pos);
        return false
      };
      if(pos == 1)
      {
        flecheUp.style.display = "none";
        if (pos == nbQuestions)
        {
          flecheDown.style.display = "none";
        }
        return;
      }
      if (pos == nbQuestions)
      {
        flecheDown.style.display = "none";
        return;
      }
      flecheUp.style.display = "block";
      flecheDown.style.display = "block";
    }

    //pos entre 1 et nbQuestions
    function editBadge(pos, id){
      $(()=>{
        $("#badgeQuestionN"+id).text(pos);
      })
    }

    function editPositionDisplay(pos, id){
      $(()=>{
        $("#containerQuestionN"+id).css("order", pos);
      })
    }

    function moveQuestion(direction, idQuestion, posQuestion){

      var nbQuestions = tableauQuestionOrder.length;
      if(posQuestion+direction<=0 || posQuestion+direction>nbQuestions){
        return;
      }
      fetch("ajax/question-move-order.php?idQuizz="+idQuizz+"&idQuestion="+idQuestion+"&oldPos="+posQuestion+"&direction="+direction)
      .then((response)=>{
        response.text()
        .then((resp) =>{
          if (resp == "ok"){
            $(()=>{
              var idCible = tableauQuestionOrder[posQuestion+direction-1];
              //Tableau edit
              tableauQuestionOrder[posQuestion+direction-1] = idQuestion;
              tableauQuestionOrder[posQuestion-1] = idCible;
              //badgeQuestion edit
              editBadge(posQuestion+direction, idQuestion);
              editBadge(posQuestion, idCible);

              //order edit
              editPositionDisplay(posQuestion+direction, idQuestion);
              editPositionDisplay(posQuestion, idCible);

              //fleches edit
              editFleches(posQuestion+direction, idQuestion);
              editFleches(posQuestion, idCible);
            });
          }
        });
      });
    }

    function saveQuestionReponse(id)
    {
      var form = new FormData(document.getElementById("editQuestionN"+id));
      fetch("ajax/question-save-edit.php", {
        method: "POST",
        body: form
      })
      .then((response) => {
        response.text()
        .then((resp) => {
          if(resp=="ok"){
            var btnCtn = document.getElementById("editQuestion_BtnCtnN"+id),
                btn = document.getElementById("editQuestion_BtnN"+id);
            btn.className = "btn btn-outline-primary float-right";
            btnCtn.className = 'fas fa-check';
            setTimeout(() => {
              btn.className = "btn btn-success float-right";
              btnCtn.className = 'far fa-edit';
            }, 3000);
          }
        })
      });
    }

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
