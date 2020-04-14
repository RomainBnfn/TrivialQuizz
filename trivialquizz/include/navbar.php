<?php
  //TODO: changer les liens
  $_locationBase = "/trivial/trivialquizz/";

  if(isset($_GET['unlog'])){
    $_SESSION['pseudo']="";
    $_SESSION['is_admin']="false";
    header("Location: $_locationBase/index.php");
    exit();
  }

  if(isset($_SESSION['is_admin'])){
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
<nav class="navbar fixed-top" role="navigation">
  <div class="container">
    <!-- Logo pour aller à la page d'accueil -->
    <div>
        <a href="<?=$_locationBase?>/index.php">
          ​<picture>
            <img src="/trivial/trivialquizz/image/logo.png" class="img-logo" alt="Logo du Trivial Quizz">
          </picture>
        </a>
    </div>
    <div style="width: 600px">
      <div class="dropdown">
        <span class="dropdown-toggle-dark dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Section Admin
        </span>
        <div class="dropdown-menu-dark dropdown-menu" aria-labelledby="dropdownMenuButton">
          <a class="dropdown-dark dropdown-item-dark dropdown-item" href="<?=$_locationBase?>/admin/theme.php">Thèmes</a>
          <a class="dropdown-dark dropdown-item-dark dropdown-item" href="<?=$_locationBase?>/admin/quizz.php">Quizzes</a>
        </div>
      </div>
    </div>
    <!-- Se connecter -->
    <?php
      if($connected){
    ?>
      <div id="hello-navbar">
        <p><?=$hello_txt?></p>
        <a class="center" href="/trivial/trivialquizz/index.php?unlog=true">
          <button type="button" class="btn btn-outline-primary" name="unlog">Déconnexion</button>
        </a>
      </div>
    <?php
      }else{
    ?>
    <div>
      <a href="/trivial/trivialquizz/register.php">
        <button type="button" class="btn btn-primary">Inscription</button>
      </a>
      <a href="/trivial/trivialquizz/log.php">
        <button type="button" class="btn btn-outline-primary">Connexion</button>
      </a>
    </div>
    <?php
      }
    ?>
  </div>
</nav>
