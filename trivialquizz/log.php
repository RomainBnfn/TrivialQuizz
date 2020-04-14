<?php
  session_start();
  require_once  "include/liaisonbdd.php";
  //TODO: Changer ça
  $admin_index_location = "/trivial/trivialquizz/admin/index.php";
  $base_location = "/trivial/trivialquizz";
  $index_location = "/trivial/trivialquizz/index.php";

  //si première essai: first_try, si echec connexion: fail
  $connection = "first_try";

  if(isset($_POST["pseudo"]) && isset($_POST["pswd"])){
    $pseudo = $_POST["pseudo"];
    $requete = $bdd -> prepare("SELECT pr_pseudo FROM profil WHERE pr_pseudo=? AND pr_password=?");
    $requete -> execute(array($pseudo,$_POST["pswd"]));
    if(empty($requete -> fetch())){
      // non connecté
      $connection = "fail";
    }else {
      // connecté
      $_SESSION['pseudo'] = $pseudo;
      $requete = $bdd -> prepare("SELECT pr_is_admin FROM profil WHERE pr_pseudo=?");
      $requete -> execute(array($pseudo));
      if(($requete -> fetch())[0] == 1){
        $_SESSION['is_admin'] = true;
        header("Location: ".$admin_index_location);
      }else {
        if(isset($_SESSION['origin'])){
          header("Location: ".$base_location."/".$_SESSION['origin']);
        }else {
          header("Location: ".$index_location);

        }
      }
    }
  }
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <?php require_once "include/header.html"?>
    <link rel="stylesheet" type="text/css" href="css/style-log.css">
    <title>Connexion - Trivial Quizz</title>
  </head>
  <body>
    <?php require_once "include/navbar.php"?>
    <section class="bandeau-principal fond-bleu">
      Connexion
    </section>
    <section class="cadre-global">
      <div class="cadre-central">
          <?php
            if($connection == "fail") {
          ?>
            <div class="msg-erreur">
              <p>Pseudo ou mot de passe non valide</p>
            </div>
          <?php
            }
          ?>
          <form class="form-padding" action="log.php" method="post">
            <div class="form-group">
              <label for="pseudo">Pseudo:</label>
              <input type="text" class="form-control" id="pseudo" placeholder="Entrer votre pseudo" name="pseudo" required>
            </div>
            <div class="form-group">
              <label for="pwd">Mot de passe:</label>
              <input type="password" class="form-control" id="pwd" placeholder="Entrer votre mot de passe" name="pswd" required>
            </div>
            <button type="submit" class="btn btn-primary">Se connecter</button>
          </form>
          <div class="redirection-log-register">
            Si vous n'êtes pas inscrit&nbsp<a href="register.php">inscrivez vous</a>
          </div>
      </div>
    </section>
  </body>
</html>
