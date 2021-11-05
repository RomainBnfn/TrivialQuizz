<?php
  require_once "../include/liaisonbdd.php";
  require_once "../include/functions.php";

  // On prépare la liste des noms de tous les quizz pour que l'utilisateur
  // ne rentre pas un nom déjà existant (pas beau).
  $listeNoms = getAllQuizzNames($bdd);

//-----------------------------------------

  $_THEMES = getAllThemesInfos($bdd);

//-----------------------------------------
?>
<!-- Les modals (Pop-up)-->
<div class="modal fade" id="modalCreationQuizz" tabindex="-1" role="dialog" aria-labelledby="modalCreationQuestion" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content modal-dark">

      <div class="modal-header" style="background-color: #48f; color: white;">
        <h5 class="modal-title" id="modalTitle">Création d'un Quizz</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form id="creationQuizz" method="POST">
        <div class="modal-body" style="padding-bottom: 0 !important; margin-bottom: 15px; margin-right: 15px;">

          <div class="form-group row">
            <label for="editGeneral_Nom" class="col-sm-2 col-form-label">
              Nom:
            </label>
            <input id="formGeneral_Nom" type="text" class="input-dark col form-control" name="nom" placeholder="Entrez le nom de votre Quizz !" autocomplete="off" required/>
            <div id="errorGeneral_Nom" class="invalid-feedback">
              Ce nom est déjà utilisé !
            </div>
          </div>

          <div class="form-group row">
            <label for="formGeneral_Desc" class="col-sm-2 col-form-label">
              Description:
            </label>
            <textarea id="formGeneral_Desc" type="text" class="input-dark col form-control" name="desc" rows="5" placeholder="Entrez la description de votre Quizz !" required></textarea>
          </div>

          <div class="form-group row">
            <label for="formGeneral_Theme" name="theme" class="col-sm-2 col-form-label">Thème :</label>
            <select class="input-dark col form-control" id="formGeneral_Theme" name="theme">
              <optgroup label="Thèmes Principaux">
              <?php
              foreach ($_THEMES as $_THEME)
              {
              ?>
                <option value="<?= $_THEME["id"] ?>"><?= $_THEME["nom"] ?></option>
              <?php
                if($_THEME["id"] == 6 && count($_THEMES) > 6){
                  echo "</optgroup><optgroup label='Thèmes personnalisés'>";
                }
              }
              ?>
              </optgroup>
            </select>
          </div>
          <div class="form-check">
            <input type="checkbox" class="form-check-input" id="passerAEdition" checked>

            <label class="col form-check-label" for="passerAEdition">
              Je directement Editer mon quizz après sa Création !
            </label>
          </div>
          <br/>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
          <input id="formCreation_Button"  class="btn btn-success" value="Créer le Quizz !" type="submit" />
        </div>
      </form>

    </div>
  </div>
</div>
<script>
  $(document).ready(function(){

    $("#creationQuizz").submit((e) => {
      e.preventDefault();

      var form = new FormData(document.getElementById("creationQuizz"));
      fetch("ajax/quizz-create.php", {
        method: "POST",
        body: form
      })
      .then((response) => {
        response.text()
        .then((resp) => {
          if($('#passerAEdition').is(':checked'))
          {
            document.location.href="quizz-edit.php?id="+resp;
          }
          else
          {
            document.location.href="quizz.php";
          }
        })
      })
    });

  });
</script>
