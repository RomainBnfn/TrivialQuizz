<link rel="stylesheet" href="https://unpkg.com/huebee@1/dist/huebee.min.css">
<!-- Les modals (Pop-up)-->
<div class="modal fade" id="modalCreationTheme" tabindex="-1" role="dialog" aria-labelledby="modalCreationTheme" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content modal-dark">

      <div class="modal-header" style="background-color: #FF8000; color: white;">
        <h5 class="modal-title" id="exampleModalLongTitle">Création de Thème</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form id="formCreationTheme" method="POST">

        <input type="hidden" name="id_Quizz" value="<?= $_QUIZZ["id"] ?>" />

        <div class="modal-body modal-custom" style="margin-left: 5px; margin-right: 15px; padding-bottom: 0;">
          <div class="form-group row">
            <label for="editGeneral_Nom" class="col-sm-2 col-form-label">
              Nom:
            </label>
            <input id="formGeneral_Nom" type="text" class="input-dark col form-control" name="nom" placeholder="Entrez le nom de votre Thème !" autocomplete="off" required/>
            <div id="errorGeneral_Nom" class="invalid-feedback">
              Ce nom est déjà utilisé !
            </div>
          </div>

          <div class="form-group row">
            <label for="formGeneral_Desc" class="col-sm-2 col-form-label">
              Description:
            </label>
            <textarea id="formGeneral_Desc" type="text" class="input-dark col form-control" name="desc" rows="5" placeholder="Entrez la description de votre Thème !" required></textarea>
          </div>

          <div class="form-group row">
            <label for="editGeneral_Nom" class="col-sm-2 col-form-label">
              Couleur:
            </label>
            <input id="couleur" type="text" class="color-input col form-control" name="couleur" placeholder="Cliquez pour choisir la couleur !" autocomplete="off" data-huebee='{ "notation": "hex" }' required/>
          </div>

          <div class="form-check">
            <input type="checkbox" class="form-check-input" id="passerAEdition" checked>

            <label class="col form-check-label" for="passerAEdition">
              Je directement Editer mon thème après sa Création !
            </label>
          </div>

          <br/>
          <br/>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
          <input id="formCreation_Button"  class="btn btn-success" value="Ajouter" type="submit" />
        </div>

      </form>

    </div>
  </div>
</div>
<script src="https://unpkg.com/huebee@1/dist/huebee.pkgd.min.js">
  var hueb = new Huebee( '.color-input', {
    // options
    notation: 'hex',
    saturations: 2,
  });
</script>
<script>
  $(document).ready(() =>{

    $("#formCreationTheme").submit((e) => {
      e.preventDefault();

      var form = new FormData(document.getElementById("formCreationTheme"));
      fetch("ajax/theme-create.php", {
        method: "POST",
        body: form
      })
      .then((response) => {
        response.text()
        .then((resp) => {
          if($('#passerAEdition').is(':checked'))
          {
            document.location.href="theme-edit.php?id="+resp;
          }
          else
          {
            document.location.href="theme.php";
          }
        })
      })
    });

  });
</script>
