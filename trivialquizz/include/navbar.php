<?php
  //TODO: changer les liens


  if(isset($_GET['unlog'])){
    $_SESSION['pseudo']="";
    $_SESSION['is_admin']="false";
    header("Location: /trivial/trivialquizz/index.php");
    exit();
  }
  $logo_link = "/trivial/trivialquizz/index.php";
  if(isset($_SESSION['is_admin'])){
    if($_SESSION['is_admin']=="true"){
      $logo_link = "/trivial/trivialquizz/admin/index.php";
      $admin = true;
    }else{
      $admin = false;
    }
  }else{
    $admin = false;
  }

  if(isset($_SESSION['pseudo'])){
    if(empty($_SESSION['pseudo'])){
      $connected = false;
    }else{
      $connected = true;
      if($admin){
        $hello_txt = "Bonjour, ".$_SESSION['pseudo']." (admin)";
      }else{
        $hello_txt = "Bonjour, ".$_SESSION['pseudo'];
      }
    }
  }else{
    $connected = false;
  }
?>
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
  <div class="container">
    <!-- Logo pour aller à la page d'accueil -->
    <div>
        <a href="<?=$logo_link?>">
          ​<picture>
            <img src="/trivial/trivialquizz/image/logo.png" class="img-logo" alt="Logo du Trivial Quizz">
          </picture>
        </a>
    </div>

    <!-- Se connecter -->
    <?php
      if($connected){
    ?>
      <div id="hello-navbar">
        <p><?=$hello_txt?></p>
        <a href="?unlog=true">
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
