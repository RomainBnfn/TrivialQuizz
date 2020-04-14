<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<!-- Les modals (Pop-up)-->
<div class="modal fade" id="modalCreationQuestion" tabindex="-1" role="dialog" aria-labelledby="modalCreationQuestion" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content modal-dark">

      <div class="modal-header" style="background-color: #64ae64; color: white;">
        <h5 class="modal-title" id="exampleModalLongTitle">Création de Question</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="formCreationQuestion" method="POST">

        <input type="hidden" name="id_Quizz" value="<?= $_QUIZZ["id"] ?>" />

        <div class="modal-body" style="padding-bottom: 0 !important;">

          <!-- =============================== TYPE DE QUESTION =============================== -->
          <div class="form-group row">
            <label for="typeQuestion" class="col-sm-3 col-form-label">Type de la Question :</label>

            <style>
              .toggle.ios, .toggle-on.ios, .toggle-off.ios {
                border-radius: 20px;
                width: 140px !important;
              }
              .toggle-on{
                width: var( --.toggle.ios-width ) !important;
              }
              .toggle-off{
                width: var( --.toggle.ios-width ) !important;
              }
              .toggle.ios .toggle-handle {
                border-radius: 20px;
                background-color: white !important;
              }
            </style>

          <input id="choix_typeQuestion" name="typeQuestion" type="checkbox" checked data-toggle="toggle" data-style="ios" data-on="Réponse Libre" data-off="QCM" data-onstyle="success" data-offstyle="info" />

          </div>

          <!-- =============================== NOM DE QUESTION ===============================-->
          <div class="form-group">
            <label for="intituleQuestion" class="col">
              <i class="far fa-question-circle" style="color: #339af0;"></i>
              Intitulé de la question :
            </label>

            <input type="text" class="input-dark form-control" name="intituleQuestion" id="intituleQuestion" autocomplete="off" placeholder="Entrez l'intitulé de la Question !" required/>
          </div>

          <hr/> <!-- ======================================================= -->


          <!-- =============================== REPONSE LIBRE a la QUESTION ===============================-->
          <div id="reponse_TypeLibre" class=" form-group">
            <label for="reponseLibre_correcte" class="col">
              <i class="far fa-check-circle" style="color: #51cf66;"></i>
              Réponse correcte :
            </label>
            <input type="text" class="input-dark form-control" name="reponseLibre_correcte" id="reponseLibre_correcte" autocomplete="off" placeholder="Entrez cette fameuse réponse !" required/>
            <small id="reponseLibre_correcte_help" class="form-text text-muted" style="color: white !important;">
              Cette réponse devra être indiquée à la lettre près.
            </small>
          </div>


          <!-- =============================== REPONSE QCM a la QUESTION =============================== -->
          <div id="reponse_TypeQCM" class="form-group">
            <label class="col">
              <i class="fas fa-th-list" style="color: #339af0;"></i>
              Liste des réponses :
              <br/>
              <small class="form-text text-muted" style="color: white !important;">
                Listez toutes les réponses qui peuvent être affichées. La première est juste, les autres fausses.
              </small>
            </label>

            <ul class="dark-background list-group col list-group-flush">
              <?php for($i = 1; $i<=4; $i++){?>
                <li class="dark-background list-group-item">

                    <div class="row">
                      <div class="col-sm-1 form-group" style="text-align: center; vertical-align: middle;">
                        <?php if($i==1){?>
                          <i class="fas fa-check-circle" style="color: #51cf66;"></i>
                        <?php }else{?>
                          <i class="fas fa-times-circle" style="color: #ff6b6b;"></i>
                        <?php } ?>
                      </div>
                      <input class="input-dark col-sm-11 form-control" name="reponseQCM_N<?=$i?>" type="text" placeholder="<?php if($i==1){?>Entrez la réponse correcte.<?php }else{?>Entrez une mauvaise réponse.<?php }?>" required>

                    </div>
                </li>
            <?php } ?>
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
<script>
  $(document).ready(() =>{
    // ---- Choix Type Question ----
    var _save_htmlLibre = $("#reponse_TypeLibre").html(),

        _save_htmlQCM = $("#reponse_TypeQCM").html();
    $("#reponse_TypeQCM").html("");

    $("#choix_typeQuestion").change(function() {
      if(this.checked) {
        _save_htmlQCM = $("#reponse_TypeQCM").html();
        $("#reponse_TypeQCM").html("");
        $("#reponse_TypeLibre").html(_save_htmlLibre);
      }
      else{
        _save_htmlLibre = $("#reponse_TypeLibre").html();
        $("#reponse_TypeLibre").html("");
        $("#reponse_TypeQCM").html(_save_htmlQCM);
      }
    });


    // ----------------------------

    $("#formCreationQuestion").submit((e) => {
      e.preventDefault();
      var form = new FormData(document.getElementById("formCreationQuestion"));
      fetch("ajax/question-create.php", {
        method: "POST",
        body: form
      })
      .then((response) => {
        response.text()
        .then((resp) => {
          console.log(resp);
          if(resp=="ok"){
          }
        })
      });
    });;
  });
</script>
