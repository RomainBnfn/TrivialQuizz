<!-- Les modals (Pop-up)-->
<div class="modal fade" id="modalConfirmationVider" tabindex="-1" role="dialog" aria-labelledby="modalConfirmationVider" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered" role="document">
    <div class="modal-content modal-dark">

      <div class="modal-header" style="background-color: #8E1C1C; color: white;">
        <h5 class="modal-title" id="exampleModalLongTitle">Confirmation</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body" style="padding-bottom: 0 !important;">
        <p>
          Confirmez vous désirer vider toutes les questions du quizz ? Les questions importées seront déliées, les autres supprimées.
        </p>
      </div>

      <div class="modal-footer">
        <form id="modalConfirmationVider" method="POST">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
          <input id="formCreation_Button"  class="btn btn-danger" value="Vider le Quizz" type="submit" />
        </form>
      </div>

    </div>
  </div>
</div>
<script>
  $(document).ready(()=>{
    $("#modalConfirmationVider").submit((e)=>{
      e.preventDefault();
      fetch("ajax/question-vider.php?idQuizz=<?= $_QUIZZ['id'] ?>")
      .then((response) =>{
        response.text()
        .then((resp)=>{
          $('#modalConfirmationVider').modal('hide');
          if(resp=="ok"){
            location.reload(true);
          }
        })
      })
    })
  });
</script>
