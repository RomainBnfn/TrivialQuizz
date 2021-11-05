<?php ?>
<!-- Modal Connexion -->
<div class="modal fade" id="modalConnexion" tabindex="-1" role="dialog" aria-labelledby="modalConnexion" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered" role="document">
    <div class="modal-content modal-dark">

      <div class="modal-body" style="padding-bottom: 0 !important; margin-bottom: 15px; margin-right: 15px;">
        <div class="div-connexion">
          <form id="formConnexion" method="POST">
          	<h4 class="card-title text-center">
              Connexion
            </h4>
          	<hr/>

            <p id="messageConnexionIncitation" class="text-center">
              Accédez à votre site préféré, connectez vous !
            </p>

            <div id="messageConnexionError" class="alert alert-danger text-center" role="alert" style="display: none">
              Le compte n'existe pas ou le Mot de passe est faux !
            </div>

          	<div class="form-group">
            	<div class="input-group">
            		<div class="input-group-prepend">
            		    <span class="input-group-text input-dark"> <i class="fa fa-user"></i> </span>
            		 </div>
            		<input name="pseudo" class="form-control input-dark" placeholder="Pseudo" type="text" autocomplete="off" required>
            	</div>
          	</div>

          	<div class="form-group">
            	<div class="input-group">
            		<div class="input-group-prepend">
            		   <span class="input-group-text input-dark"> <i class="fa fa-lock"></i> </span>
            		 </div>
            	    <input name="password" class="form-control input-dark" placeholder="******" type="password" autocomplete="off" required>
            	</div>
          	</div>

            <hr/>

          	<div class="form-group">
          	   <button id="btnConnexion" type="submit" class="btn btn-primary btn-block">
                 Connexion
               </button>
          	</div>
          	<p id="changeConToInsc" class="text-center">
              <span class="blue-text">
                Pas encore inscrit ?
              </span>
            </p>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script>

  $("#changeConToInsc").click(()=>{
    $('#modalInscription').modal('show');
    $('#modalConnexion').modal('hide');
  });

  $("#formConnexion").submit((e)=>{
    e.preventDefault();

    $("#btnConnexion").html('<i class="fas fa-sync-alt fa-spin"></i>');

    var form = new FormData(document.getElementById("formConnexion"));
    fetch("<?= $index_location ?>/ajax/tryLog.php", {
      method: "POST",
      body: form
    })
    .then((response) => {
      response.text()
      .then((resp) => {
        console.log(resp);
        if(resp == "fail"){
          $("#messageConnexionIncitation").css("display", "none");
          $("#messageConnexionError").css("display", "block");
          $("#btnConnexion").html("Connexion");
          setTimeout(() => {
            $("#messageConnexionIncitation").css("display", "block");
            $("#messageConnexionError").css("display", "none");
          }, 5000);
        }
        else if(resp == "ok"){
          $('#modalConnexion').modal('hide');
          location.reload(true);
        }
      })
    });

  })
</script>
