<fieldset class="form-group row">
  <div class="row">
    <legend class="col-form-label col-sm-3 pt-0">Type de Question :</legend>
    <div class="col-sm-9">
      <div class="col-sm-5 custom-control custom-radio custom-control-inline">
        <input type="radio" id="RepLibre" name="TypeQuestion" class="custom-control-input" checked>
        <label class="custom-control-label" for="RepLibre">Réponse Libre</label>
      </div>
      <div class="col-sm-2 custom-control custom-radio custom-control-inline">
        <input type="radio" id="QCM" name="TypeQuestion" class="custom-control-input">
        <label class="custom-control-label" for="QCM">QCM</label>
      </div>
    </div>
  </div>
</fieldset>

<fieldset class="form-group row">
  <div class="row">
    <legend class="col-form-label col-sm-3 pt-0">Intitulé de la Question :</legend>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="inputAddress" placeholder="Entrez l'intitulé de la question !" required>
    </div>
  </div>
</fieldset>

<input type="hidden" name="id_Quizz" value="<?= $_QUIZZ["id"] ?>" />

<hr/> <!-- ======================================================= -->


<div class="form-group" id="creationQuestion_ReponseLibre">
  <fieldset class="row">
    <div class="row">
      <legend class="col-form-label col-sm-3 pt-0">La réponse :</legend>
      <div class="col-sm-9">
        <input type="text" class="form-control" id="inputAddress" placeholder="La bonne réponse de la question libre." required>
      </div>
    </div>
  </fieldset>
</div>

<div id="creationQuestion_ReponseQCM" style="">
  <div class="form-group interligne">
    <div class="row d-flex justify-content-between">
      <h4>Les réponses :</h4>
      <div>
        <button type="button" class="btn btn-outline-success">Ajouer</button>
        <button type="button" class="btn btn-outline-danger">Vider</button>
      </div>
    </div>
    <div>
      Entrez les différentes réponses possibles et cochez celles qui sont justes.
    </div>
    <div class="row d-flex justify-content-between">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
      </div>
      <div class="col-sm-11">
        <input type="text" class="form-control" id="inputAddress" placeholder="La bonne réponse de la question libre." required>
      </div>
      <div>
        <button type="button" class="btn btn-danger">
          <i class="fas fa-trash-alt" style="color: #ffffff;"></i>
        </button>
      </div>
    </div>
    <div class="row d-flex justify-content-between">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
      </div>
      <div class="col-sm-11">
        <input type="text" class="form-control" id="inputAddress" placeholder="La bonne réponse de la question libre." required>
      </div>
      <div>
        <button type="button" class="btn btn-danger">
          <i class="fas fa-trash-alt" style="color: #ffffff;"></i>
        </button>
      </div>
    </div>

  </div>
</div>
