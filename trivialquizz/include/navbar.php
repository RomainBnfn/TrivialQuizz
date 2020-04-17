<?php
  require_once "index_location.php";
  require_once "liaisonbdd.php";
  require_once "functions.php";
  $isConnected ="";
  $isAdmin = "";

  if(!empty($_SESSION) && isset($_SESSION['pseudo'])){ // Connecté
    $isConnected = 1;
    if(isset($_SESSION['is_admin'])){
      $hello_txt = "Bonjour, ". $_SESSION['pseudo'];
      $isAdmin = 1;
    }
    else{
      $hello_txt = "Bonjour, ".$_SESSION['pseudo'];
    }
  }

?>
<link rel="stylesheet" type="text/css" href="<?=$index_location?>/css/modal.css">
<nav class="navbar navbar-expand-md fixed-top" role="navigation">
  <div class="navbar-center">

    <a class="navbar-brand" href="<?=$index_location?>/index.php" style="padding: 0;">
      ​<picture>
        <img src="<?=$index_location?>/image/logo.png" class="img-logo" alt="Logo du Trivial Quizz">
      </picture>
    </a>

    <div class="collapse navbar-collapse" id="collapseMenu">
      <ul class="navbar-nav">
        <?php if($isAdmin){ ?>
          <li class="navbar-item dropdown">

            <a class="nav-link dropdown-toggle dropdown-toggle-dark" data-toggle="dropdown" data-target="dropD" >
              Administration
              <span class"caret"></span>
            </a>

            <div class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropD">
              <a class="dropdown-dark dropdown-item-dark dropdown-item" href="<?=$index_location?>/admin/theme.php">
                Editer des thèmes
              </a>
              <a class="dropdown-dark dropdown-item-dark dropdown-item" href="<?=$index_location?>/admin/quizz.php">
                Editer des quizzes
              </a>
            </div>
          </li>
        <?php } ?>

        <?php if($isConnected){ ?>
          <li class="navbar-item">
            <a class="nav-link">
              <i class="fas fa-user"></i>
              <?= $hello_txt ?>
              <span class"caret"></span>
            </a>
          </li>

          <li class="navbar-item">
            <a class="nav-link" href="#">
              <button type="button" class="btn btn-outline-primary" onclick="logout()">Deconnexion</button>
            </a>
          </li>

        <?php } else { ?>

          <li class="navbar-item">
            <button id="boutonInscription" type="button" class="btn btn-primary button-open-modal" data-toggle="modal" data-target="#modalInscription">
              Inscription
            </button>
          </li>

          <li class="navbar-item">
            <button id="boutonConnexion" type="button" class="btn btn-outline-primary button-open-modal" data-toggle="modal" data-target="#modalConnexion">
              Connexion
            </button>
          </li>

        <?php } ?>
      </ul>
    </div>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapseMenu" aria-controls="menuNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <i class="fas fa-bars fa-lg" style="color: white !important"></i>
    </button>
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
