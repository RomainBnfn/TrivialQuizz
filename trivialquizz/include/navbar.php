<?php
  require_once "index_location.php";
  require_once "liaisonbdd.php";
  require_once "functions.php";
  $isConnected ="";

  if(!empty($_SESSION) && isset($_SESSION['pseudo'])){ // Connecté
    $isConnected = 1;
    if(isset($_SESSION['is_admin'])){
      $hello_txt = "(Admin) Bonjour, ". $_SESSION['pseudo'];
    }
    else{
      $hello_txt = "Bonjour, ".$_SESSION['pseudo'];
    }
  }

?>
<link rel="stylesheet" type="text/css" href="<?=$index_location?>/css/modal.css">
<nav class="navbar fixed-top navbar-expand-md navbar" role="navigation">
  <div>
      <a href="<?=$index_location?>/index.php">
        ​<picture>
          <img src="<?=$index_location?>/image/logo.png" class="img-logo" alt="Logo du Trivial Quizz">
        </picture>
      </a>
  </div>

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#menuNavbar" aria-controls="menuNavbar" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon" style="color: white !important"></span>
  </button>

  <div class="collapse navbar-collapse" id="menuNavbar">
    <div class="dropdown">

      <span class="dropdown-toggle-dark dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Administration
      </span>

      <div class="dropdown-menu-dark dropdown-menu" aria-labelledby="dropdownMenuButton">

        <a class="dropdown-dark dropdown-item-dark dropdown-item" href="<?=$index_location?>/admin/theme.php">
          Editer des thèmes
        </a>
        <a class="dropdown-dark dropdown-item-dark dropdown-item" href="<?=$index_location?>/admin/quizz.php">
          Editer des quizzes
        </a>
      </div>
    </div>

    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
      <?php if($isConnected){ ?>
        <p>
          <?= $hello_txt ?>
        </p>
        <li>
          <button type="button" class="btn btn-outline-primary" onclick="logout()">Deconnexion</button>
        </li>
      <?php } else { ?>
        <li>
          <button id="boutonInscription" type="button" class="btn btn-primary button-open-modal" data-toggle="modal" data-target="#modalInscription">
            Inscription
          </button>
        </li>
        <li>
          <button id="boutonConnexion" type="button" class="btn btn-outline-primary button-open-modal" data-toggle="modal" data-target="#modalConnexion">
            Connexion
          </button>
        </li>
      <?php } ?>
    </ul>

  </div>
</nav>
<?php
  require_once "modals/connexion.php";
  require_once "modals/inscription.php";
?>
<script type="text/javascript">
  function logout(){
    fetch("<?=$index_location?>/ajax/unlog.php")
    .then((response)=>{
      response.text()
      .then((resp)=>{
        console.log(resp);
        location.reload(true);
      })
    })
  }
</script>
