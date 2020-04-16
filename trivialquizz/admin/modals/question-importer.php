<?php
  require_once "../include/liaisonbdd.php";
  require_once "../include/functions.php";

//-----------------------------------------

  $_QUIZZES = getAllQuizzesInfos($bdd);
  $_NBQUESTIONS = getNumbersOfQuestionsOfAllQuizzes($bdd);

  $id_Quizz = $_QUIZZ["id"];
//-----------------------------------------
?>
<!-- Les modals (Pop-up)-->
<div class="modal fade" id="modalImportationQuestion" tabindex="-1" role="dialog" aria-labelledby="modalImportationQuestion" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content modal-dark">

      <div class="modal-header" style="background-color: #48f; color: white;">
        <h5 class="modal-title" id="modalTitle">Importation d'une Question</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>


      <form id="importationQuestion" method="POST">
        <div class="modal-body" style="padding-bottom: 0 !important; margin-bottom: 15px; margin-right: 15px;">
          <?php
            $i = 0;
            foreach ($_QUIZZES as $id => $_QUIZZ_LOOP) {
              if($_QUIZZ_LOOP["id"] == $id_Quizz){
                continue;
              }
              if(!isset($_NBQUESTIONS["$id"]) || $_NBQUESTIONS["$id"] < 1){
                continue;
              }
              $i++;
            }
            if($i == 0){
          ?>
          <p>
            Il n'y a aucune question à importer.. Créez en quelques unes avant de tester cette fonctionnalité !
          </p>
        <?php } else { ?>


          <div class="form-group">
            <label for="selectImportQuizz" class="main-label form-control-label">
              <i class="fas fa-directions"></i>
              Quizz:
            </label>

            <select id="selectImportQuizz" class="input-dark dropdown form-control selectpicker" data-live-search="true" required >
              <option value="-1" selected disabled>Cliquez pour choisir un Quizz !</option>
              <?php

              foreach ($_QUIZZES as $id => $_QUIZZ_LOOP) {
                if($_QUIZZ_LOOP["id"] == $_QUIZZ["id"])
                  continue;
                if($_NBQUESTIONS["$id"] < 1)
                  continue;

                 ?>
                <option value="<?= $_QUIZZ_LOOP["id"] ?>"><?= $_QUIZZ_LOOP["nom"] ?></option>
              <?php
              }?>
            </select>
            <small class="form-text text-muted" style="color: white !important;">
              Sélectionnez le quizz pour voir ses questions. (Seuls les quizzes avec des questions sont affichés ici)
            </small>
          </div>

          <div id="divImportQuestions" class="form-group" style="display: none">
            <label for="selectImportQuestions" class="main-label form-control-label" style="color: #AAFFAA !important">
              <i class="fas fa-question-circle"></i>
              Les questions:
            </label>

            <select id="selectImportQuestions" class="input-dark dropdown form-control selectpicker" data-live-search="true" id="select">

            </select>

          </div>

        <?php } ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
          <input id=""  class="btn btn-success" value="Ajouter" type="submit" />
        </div>
      </form>


    </div>
  </div>
</div>
<script>
  $(document).ready(()=>{
    $("#selectImportQuizz").change(()=>{
      var idQuizz = $("#selectImportQuizz").val();
      fetch("ajax/getQuestionsToDisplay.php?idQuizzCible="+idQuizz+"&idQuizzSource=<?=$id_Quizz?>")
      .then((response)=>{
        response.text()
        .then((resp)=>{
          $("#divImportQuestions").css("display", "block");
          $("#selectImportQuestions").html(resp);
        })
      });

    });

    $("#importationQuestion").submit((e)=>{
      e.preventDefault();
      if($("#selectImportQuizz").val() == null || $("#selectImportQuestions").val() == null){
        alert("Il faut selectionner les trucs, abuses pas.");
      }
      else{//OK
        var idQuest = $("#selectImportQuestions").val();
        fetch("ajax/question-lier.php?idQuizz=<?=$id_Quizz?>&idQuest="+idQuest)
        .then((response)=>{
          response.text()
          .then((resp)=>{
            console.log(resp);
            if(resp=="ok"){
              $('#modalImportationQuestion').modal('hide');
              location.reload(true);
            }
          })
        })
      }
    })
  })


</script>
