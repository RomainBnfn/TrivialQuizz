<?php
  session_start();
  require_once "../include/index_location.php";

  if(empty($_SESSION) || !isset($_SESSION['pseudo'])){ // Non onnecté
    header("Location: $index_location/index.php"); // Les admins ne restent pas sur cette page, ils vont direct ailleurs
    exit();
  }

  if(isset($_SESSION['is_admin']))
  {
    header("Location: $index_location/admin/theme.php"); // Les admins ne restent pas sur cette page, ils vont direct ailleurs
    exit();
  }

  //
  // Cette page est pour créer des nouveaux admins
  //

  require_once "../include/liaisonbdd.php";
  require_once "../include/functions.php";

  ?>
  <!doctype html>
  <html lang="fr">
  <head>
    <title>Panel d'Admin</title>
    <?php require_once "../include/header.php"?>
    <link rel="stylesheet" href="../css/cards.css" />
    <link rel="stylesheet" href="../css/modal.css" />
  </head>
  <body>
    <?php require_once "../include/navbar.php"?>

    <div class="bandeau-principal fond-bleu">
      Devenir Admin
    </div>

    <div class="cadre-global">
      <div class="cadre-central">

        <a class="blue-text" href="../index.php">
          <i class="fas fa-arrow-left" style="height: 2.5em;"></i>
          Retour
        <a/>

        <!-- DEBUT: Section des Thèmes-->
        <div class="cadre-admin-border">
          <div class="div-admin">
            <form id="formAdmin" method="POST">
              <h4 class="card-title text-center">
                Devenir admin
              </h4>
            	<hr/>

              <div id="messageAdminError" class="alert alert-danger text-center" role="alert" style="display: none">
                La clef rentrée est erronée !
              </div>
              <p id="messageAdminIncitation" class="text-center">
                Pour devenir admin, entrez la clef admin.
              </p>

            	<div class="form-group">
              	<div class="input-group">
              		<div class="input-group-prepend">
              		    <span class="input-group-text input-dark">
                        <i class="fa fa-key"></i>
                      </span>
              		 </div>
              		<input id="tokenAdmin" name="token" class="form-control input-dark" placeholder="Clef Token" type="text" autocomplete="off" required>
              	</div>
            	</div>

            	<div class="form-group">
            	   <button id="btnAdmin" type="submit" class="btn btn-primary btn-block">
                   Valider
                 </button>
            	</div>

              </form>
            </div>
          </article>
        </div>
        <!-- FIN: Liste des Thèmes-->

      </div>
      <!-- FIN: Section des Thèmes -->
    </div>
    <?php require_once "../include/footer.html" ?>
    <?php require_once "../include/script.html"?>
    <script>
      $(document).ready(function(){

        $("#formAdmin").submit((e)=>{
          e.preventDefault();

          $("#btnAdmin").html('<i class="fas fa-sync-alt fa-spin"></i>');

          fetch("ajax/tryUpgradeAdmin.php?pseudo=<?= $_SESSION['pseudo'] ?>&token="+$("#tokenAdmin").val())
          .then((response)=>{
            response.text()
            .then((resp)=>{
              console.log(resp);
              if(resp == "ok"){
                location.reload(true);
              }
              else{
                $("#messageAdminError").css("display", "block");
                $("#btnAdmin").html("Valider");
                setTimeout(() => {
                  $("#messageAdminError").css("display", "none");
                }, 3000);
              }
            })
          });
        })
      });
    </script>

  </body>
  </html>
