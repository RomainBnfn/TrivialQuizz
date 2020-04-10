<?php
  require_once "../include/liaisonbdd.php";
  require_once "../include/functions.php";

//-----------------------------------------

  $_QUIZZES = getAllQuizzesInfos($bdd);
  $_QUESTIONS = tryLoadAllQuestions($bdd);

//-----------------------------------------
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" rel="stylesheet" />
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" />
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



      <div class="modal-body" style="padding-bottom: 0 !important; margin-bottom: 15px; margin-right: 15px;">

        <form id="importationQuestion" method="POST">
          <div class="form-group row">
            <label for="" class="col-sm-2 form-control-label">Question</label>
            <div class="col-sm-10">
              <select class="form-control selectpicker" id="select-country" data-live-search="true">
                <?php
                $id = -1;
                foreach ($_QUESTIONS as $_QUESTION) {
                  if($id != $_QUESTION["idQuizz"]){
                    if($id != -1){
                      echo "</optgroup>";
                    }
                    $id = $_QUESTION["idQuizz"];
                    $name = $_QUIZZES["$id"]["nom"];
                    echo "<optgroup label='$name'>";
                  } ?>
                  <option data-tokens="<?= $_QUESTION["lib"] ?>"><?= $_QUESTION["lib"] ?></option>
                <?php
                }?>
                </optgroup>
              </select>
            </div>
          </div>
        </form>

      </div>

      <div class="modal-footer">
      </div>


    </div>
  </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
<script>
$(() => {
  $('#select-country').selectpicker();
});
</script>
