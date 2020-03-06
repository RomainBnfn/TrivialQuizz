<?php
  if(!isset($_SESSION))
  {
    session_start();
  }
  //TODO: Changer la location
  $index_location = "/github/trivialquizz/admin/index.php";
?>
<nav class="navbar sticky-top navbar-light bg-light">
  <div class="container">
    <div>
      <a href=<?= $index_location ?>>
        <!-- TODO: CHANGER LE CHEMIN D'ACCES -->
        <img src="/github/trivialquizz/image/logo.png" class="img-logo" alt="Logo du Trivial Quizz" />
      </a>
    </div>
    <div>
      <div>
        Un onglet
      </div>
      <div>
        Un autre
      </div>
    </div>
    <div>
      <span>
        <button type="button" class="btn btn-primary">Inscription</button>
        <button type="button" class="btn btn-outline-primary">Connexion</button>
      </span>
    </div>
  </div>
</nav>
