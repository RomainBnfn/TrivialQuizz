<!-- Les modals (Pop-up)-->
<div class="modal fade" id="modalConfirmationVider" tabindex="-1" role="dialog" aria-labelledby="modalConfirmationVider" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered" role="document">
    <div class="modal-content modal-dark">

      <div class="modal-header" style="background-color: green; color: white;">
        <h5 class="modal-title" id="exampleModalLongTitle">Confirmation</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body" style="padding-bottom: 0 !important;">

      </div>

      <div class="modal-footer">
        <form id="modalConfirmationVider" method="POST">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
          <input id="formCreation_Button"  class="btn btn-success" value="Ajouter" type="submit" />
        </form>
      </div>

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
