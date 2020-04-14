<?php
  session_start();
  require_once  "include/liaisonbdd.php";

  echo file_get_contents('php://input');
  //TODO: Changer ça
  $base_location = "/trivial/trivialquizz";
  $index_location = "/trivial/trivialquizz/index.php";

  //lien pour revenir à la page d'avant inscription après l'inscription
  if(isset($_SESSION['origin'])){
    $path = $base_location."/".$_SESSION['origin'];
  }else {
    $path = $index_location;
  }

  $isRegisterValid = false;

  if(isset($_POST['pseudo']) && isset($_POST['pswd'])) {
    $requete = $bdd -> prepare("SELECT pr_pseudo FROM profil WHERE pr_pseudo=?");
    $requete -> execute(array($_POST['pseudo']));
    $is_pseudo_available = empty($requete -> fetch());
    if($is_pseudo_available){
      $requete = $bdd -> prepare("INSERT INTO profil (pr_pseudo, pr_password) VALUES (?,?)");
      $isRegisterValid = $requete -> execute(array($_POST['pseudo'],$_POST['pswd']));
      $_SESSION['pseudo'] = $_POST['pseudo'];
      $_SESSION['is_admin'] = false;
    }
  }
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <?php require_once "include/header.html"?>
    <title>Inscription - Trivial Quizz</title>
    <link rel="stylesheet" type="text/css" href="css/style-register.css">
  </head>
  <body>
    <?php require_once "include/navbar.php"?>
    <section class="bandeau-principal fond-bleu">
      Inscription
    </section>
    <section class="cadre-global">
      <div class="cadre-central">
        <?php
          if($isRegisterValid){
        ?>
        <p>Vous êtes inscrit ! Cliquer sur "suivant" pour être ramené sur la page d'avant inscription.</p>
        <a href="<?=$path?>">
          <button type="button" class="btn btn-primary" name="suivant">Suivant</button>
        </a>
        <?php
          }else{
        ?>
        <form id="form" class="form-padding" action="register.php" method="post">
          <div class="form-group">
            <label for="uname">Pseudo:</label>
            <input type="text" class="form-control" placeholder="Entrer votre pseudo" name="pseudo" required>
            <?php
            if(isset($is_pseudo_available)){
              if(!$is_pseudo_available){
                ?>
                <div class="invalid-feedback force-display">Ce pseudo est déja utilisé.</div>
                <?php
              }
            }
            ?>
          </div>
          <div class="form-group">
            <label for="pwd">Mot de passe:</label>
            <input type="password" class="form-control" placeholder="Entrer votre mot de passe" name="pswd" required>
          </div>
          <div class="form-group">
            <label for="conf-pwd" class="line">Confirmation mot de passe:</label>
            <input type="password" class="form-control"  placeholder="Confirmer votre mot de passe" name="conf-pswd" required>
            <div id="invalid-conf-feedback" class="invalid-feedback">Confirmation invalide</div>
            <div id="valid-conf-feedback" class="valid-feedback">Ok.</div>
          </div>
          <button type="submit" class="btn btn-primary">S'inscrire</button>
        </form>
        <div class="redirection-log-register">
          Si vous êtes déjâ inscrit&nbsp<a href="log.php">connecter vous</a>
        </div>
        <?php
          }
        ?>
      </div>
    </section>
    <?php require_once "js/script.html" ?>
    <script type="text/javascript" src="js/form-register.js"></script>
  </body>
</html>
