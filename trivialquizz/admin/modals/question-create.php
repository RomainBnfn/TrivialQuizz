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
            <small id="reponseLibre_correcte_help" class="form-text text-muted">Cette réponse devra être indiquée à la lettre près.</small>
          </div>


          <!-- =============================== REPONSE QCM a la QUESTION =============================== -->
          <div id="reponse_TypeQCM" class="form-group">
            <label class="col">
              <i class="fas fa-th-list" style="color: #ff922b;"></i>
              Liste des réponses :
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
<script>
  $(document).ready(() =>{
    // ---- Choix Type Question ----
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
      $("#reponse_TypeQCM").html(htmlQCM);
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
          if(resp=="ok"){
            document.location.reload(true);
          }
        })
      });
    });;
  });
</script>
