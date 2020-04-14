<?php
  require_once "../include/liaisonbdd.php";
  require_once "../include/functions.php";

//-----------------------------------------

  $_QUIZZES = getAllQuizzesInfos($bdd);

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



      <div class="modal-body" style="padding-bottom: 0 !important; margin-bottom: 15px; margin-right: 15px;">
        <form id="importationQuestion" method="POST">
          <div class="form-group">
            <label for="" class="col form-control-label">Question</label>
            <div class="col">
              <select class="input-dark dropdown form-control selectpicker" data-live-search="true" id="select">
                <?php
                $id = -1;
                foreach ($_QUESTIONS as $_QUESTION) {
                  if($_QUESTION["idQuizz"] == $_QUIZZ["id"]) // Ne pas importer ses propres questions
                    break;
                  if($id != $_QUESTION["idQuizz"]){
                    if($id != -1){
                      echo "</optgroup>";
                    }
                    $id = $_QUESTION["idQuizz"];
                    $name = $_QUIZZES["$id"]["nom"];
                    echo "<optgroup label='Quizz: $name'>";
                  } ?>
                  <option value="<?= $_QUESTION["id"] ?>"><?= $_QUESTION["lib"] ?></option>
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
<script>

</script>
