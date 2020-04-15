<?php
  //TODO: changer les liens
  $_locationBase = "/trivial/trivialquizz";

  if(isset($_GET['unlog'])){
    $_SESSION['pseudo']="";
    $_SESSION['is_admin']="false";
    header("Location: $_locationBase/index.php");
    exit();
  }

  if(isset($_SESSION['is_admin']) && isset($_SESSION['pseudo'])){
    if($_SESSION['is_admin']=="true"){
      $hello_txt = "Bonjour, ".$_SESSION['pseudo']." (admin)";
    }
  }
  $connected = 0;
  if(isset($_SESSION['pseudo'])){
    if(!empty($_SESSION['pseudo'])){
      $connected = 1;
      $hello_txt = "Bonjour, ".$_SESSION['pseudo'];
    }
  }
?>
<nav class="navbar fixed-top navbar-expand-md navbar" role="navigation">
  <div>
      <a href="<?=$_locationBase?>/index.php">
        ​<picture>
          <img src="<?=$_locationBase?>/image/logo.png" class="img-logo" alt="Logo du Trivial Quizz">
        </picture>
      </a>
  </div>

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#menuNavbar" aria-controls="menuNavbar" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon" style="color: white !important"></span>
  </button>

  <div class="collapse navbar-collapse" id="menuNavbar">
    <div class="dropdown">

      <span class="dropdown-toggle-dark dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Section Admin
      </span>

      <div class="dropdown-menu-dark dropdown-menu" aria-labelledby="dropdownMenuButton">

        <a class="dropdown-dark dropdown-item-dark dropdown-item" href="<?=$_locationBase?>/admin/theme.php">Thèmes</a>
        <a class="dropdown-dark dropdown-item-dark dropdown-item" href="<?=$_locationBase?>/admin/quizz.php">Quizzes</a>
      </div>
    </div>

    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
      <li>
        <a href="<?=$_locationBase?>/register.php">
          <button type="button" class="btn btn-primary">Inscription</button>
        </a>
      </li>
      <li>
        <a href="<?=$_locationBase?>/log.php">
          <button type="button" class="btn btn-outline-primary">Connexion</button>
        </a>
      </li>
    </ul>

  </div>
</nav>
