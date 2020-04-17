<!-- Modal Connexion -->
<div class="modal fade" id="modalInscription" tabindex="-1" role="dialog" aria-labelledby="modalInscription" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered" role="document">
    <div class="modal-content modal-dark">

      <div class="modal-body" style="padding-bottom: 0 !important; margin-bottom: 15px; margin-right: 15px;">
        <div class="div-connexion">
          <form id="formInscription" method="POST">
          	<h4 class="card-title text-center">
              Inscription
            </h4>
          	<hr/>
            <p id="messageInscriptionIncitation" class="text-center">
              Ce site est fait pour vous, alors inscrivez vous !
            </p>
            <div id="messageInscriptionError" class="alert alert-danger text-center" role="alert" style="display: none">
              Une erreur est survenue ! Veillez réessayer.
            </div>

          	<div class="form-group">
            	<div class="input-group">
            		<div class="input-group-prepend">
            		    <span class="input-group-text input-dark"> <i class="fa fa-user"></i> </span>
            		 </div>
            		<input id="inscriptionPseudo" name="pseudo" class="form-control input-dark" autocomplete="off" placeholder="Pseudo" type="text" required>
                <div id="invalid-pseudo-feedback" class="invalid-feedback">Le pseudo est déjà utilisé !</div>
                <div id="valid-pseudo-feedback" class="valid-feedback">Le pseudo est libre !</div>
              </div>
          	</div>

          	<div class="form-group">
            	<div class="input-group">

            		<div class="input-group-prepend">
            		   <span class="input-group-text input-dark"> <i class="fa fa-lock"></i> </span>
            		 </div>

            	    <input id="inscriptionPassword" name="password" autocomplete="off" class="form-control input-dark" minlength="6" placeholder="Entrez un mot de passe" type="password" required>
                  <div id="invalid-pw-feedback" class="invalid-feedback">Le mot de passe doit faire au moins 6 caractères.</div>
              </div>
          	</div>

            <div class="form-group">
            	<div class="input-group">

            		<div class="input-group-prepend">
            		   <span class="input-group-text input-dark"> <i class="fa fa-lock"></i> </span>
            		 </div>

            	    <input id="inscriptionConfirmationPassword" autocomplete="off" name="password-confirmation" class="form-control input-dark" placeholder="Confirmer ce mot de passe" type="password" required>
                  <div id="invalid-conf-feedback" class="invalid-feedback">Les deux mots de passe ne correspondent pas.</div>
                  <div id="valid-conf-feedback" class="valid-feedback">Les mots de passe sont identiques.</div>

              </div>
          	</div>

            <hr/>

          	<div class="form-group">
          	   <button id="btnInscription" type="submit" class="btn btn-success btn-block">Inscription</button>
          	</div>
          	<p id="changeInscToCon" class="text-center">
              <span class="blue-text">
                Déjà inscrit ?
              </span>
            </p>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  var listePseudo = <?=json_encode(getAllPseudo($bdd))?>;

  function updatePwd(pw, cfpw){
    if(pw.val() != cfpw.val()){
      $('#invalid-conf-feedback').css('display','inline');
      $('#valid-conf-feedback').css('display','none');
    }else{
      $('#invalid-conf-feedback').css('display','none');
      $('#valid-conf-feedback').css('display','inline');
    }
  }

  $(document).ready(function(){

    $("#changeInscToCon").click(()=>{
      $('#modalInscription').modal('hide');
      $('#modalConnexion').modal('show');
    });

    $("#inscriptionPseudo").keyup(()=>{
      var pseudo = $("#inscriptionPseudo").val();
      if(listePseudo != null && pseudo != null && listePseudo.includes(pseudo)){
        $('#invalid-pseudo-feedback').css('display','inline');
        $('#valid-pseudo-feedback').css('display','none');
      }
      else{
        $('#invalid-pseudo-feedback').css('display','none');
        $('#valid-pseudo-feedback').css('display','inline');
      }
    });

    $("#inscriptionPassword").keyup(()=>{
      var pw = $("#inscriptionPassword");
      if(pw.val().length < 6){
        $('#invalid-pw-feedback').css('display','inline');
      }
      else{
        $('#invalid-pw-feedback').css('display','none');
      }
      updatePwd(pw, $("#inscriptionConfirmationPassword"));
    });

    $("#inscriptionConfirmationPassword").keyup(()=>{
      var pw = $("#inscriptionPassword"),
          cfpw = $("#inscriptionConfirmationPassword");
      updatePwd(pw, cfpw);
    });

    $('#formInscription').submit((e)=>{
      e.preventDefault();

      $("#btnInscription").html('<i class="fas fa-sync-alt fa-spin"></i>');

      var form = new FormData(document.getElementById("formInscription"));
      fetch("<?= $index_location ?>/ajax/tryRegister.php", {
        method: "POST",
        body: form
      })
      .then((response) => {
        response.text()
        .then((resp) => {
          if(resp == "fail"){
            $("#messageInscriptionIncitation").css("display", "none");
            $("#messageInscriptionError").css("display", "block");
            $("#btnInscription").html("Inscription");
            setTimeout(() => {
              $("#messageInscriptionIncitation").css("display", "block");
              $("#messageInscriptionError").css("display", "none");
            }, 5000);
          }
          else if(resp == "ok"){
            $('#modalInscription').modal('hide');
            location.reload(true);
          }
        })
      });

    });

  });
</script>
