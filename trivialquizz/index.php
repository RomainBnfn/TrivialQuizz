<?php
  session_start();
  require_once "include/liaisonbdd.php";
  require_once "include/functions.php";

  $themesPrincipaux = getAllThemesPrincipauxInfos($bdd);
  $themesCustoms = getAllThemesPersoInfos($bdd);

  $ids = array();
  $i = 0;
  foreach ($themesPrincipaux as $th) {
    $ids[$i++] = $th['id'];
  }

  $R = 200; //rayon
  $r = 0; // petite marge
  $G = 1.3; // grandissement focus/unfocus
  $L = 2*($r+$G*$R); // hauteur/largeur de la viewBox
  $c = $L/2; // centre de la viewBox
  $fontSizeTextUnfocus = $R/13;
  $fontSizeTextFocus = $R/11;

  $pathFocus = generatePath($r, $G*$R, $c, $ids);
  $pathUnfocus = generatePath($r, $R, $c, $ids);

  $coordTextFocus = generateCoordText($r, $G*$R, $c, $ids);
  $coordTextUnfocus = generateCoordText($r, $R, $c, $ids);

  $_NBOFQUIZZOFTHEME = getNumbersOfQuizzesOfThemes($bdd);

?>
<!doctype html>
<html lang="fr">
<head>
  <title>Trivial Quizz</title>
  <?php require_once "include/header.html"?>
  <link rel="stylesheet" type="text/css" href="css/style-index.css">
  <link rel="stylesheet" type="text/css" href="css/card-2.css">
  <link rel="stylesheet" type="text/css" href="css/modal.css">
</head>
<body>
  <?php require_once "include/navbar.php"?>
  <section class="bandeau-principal fond-bleu">
    Accueil
  </section>
  <section class="cadre-global">
    <div class="cadre-central">
      <article class="container">

        <div class="titre1">
          <h1>Thèmes classique</h1>
        </div>

        <div class="center div-roue">
            <svg id="roue-theme-classique" viewBox="0 0 <?="$L $L"?>">
              <?php
              $i = 0;
              foreach ($themesPrincipaux as $theme) {
              ?>
                <path class="theme<?=$theme['id']?>" d="<?=$pathUnfocus[$theme['id']]?>" fill="<?=$theme["couleur"]?>"/>
                <text id="th-text<?=$theme['id']?>" fill="#fff" x="<?=$coordTextUnfocus[$theme['id']][0]?>" y="<?=$coordTextUnfocus[$theme['id']][1]?>"><?=$theme["nom"]?></text>
                <path class="bt-theme theme<?=$theme['id']?>" d="<?=$pathUnfocus[$theme['id']]?>" fill="#ffffff00" stroke="#222" stroke-width="1" onclick="clickMainTheme(<?=$theme['id']?>)"/>
              <?php
              }
              ?>
              </svg>
          </div>
          <p id="th-desc"></p>
          <a href="">
            <button id="btn-quizz-smartphone" type="button" name="button" class="btn btn-primary btn-block">Voir les quizz</button>
          </a>
      </article>
      <?php
      if(!empty($themesCustoms)){
      ?>
      <article class="container">
        <h1 class="titre1">Thèmes personnalisés</h1>
        <div class="card-columns">
        <?php
          foreach ($themesCustoms as $id => $theme) {
            if(!isset($_NBOFQUIZZOFTHEME[$id]) || $_NBOFQUIZZOFTHEME[$id] <=0) {
              continue;
            }
        ?>
          <a class="card ripple-container dynamic-shadow"  href="quizz.php?theme=<?=$theme['id']?>" style="background-color: <?=$theme['couleur']?>">
              <div class="card-body">
                <h3><?=$theme['nom']?></h3>
                <p><?=$theme['desc']?></p>
            </div>
          </a>
        <?php
          }
        ?>
        </div>
      </article>
      <?php
      }
      ?>
    </div>
  </section>
  <?php require_once "include/script.html"?>

  <script type="text/javascript" src="js/animation_roue.js"></script>
  <script type="text/javascript" src="js/ripple.js"></script>
  <script type="text/javascript">
    var pathFocus = <?=json_encode($pathFocus)?>,
      pathUnfocus = <?=json_encode($pathUnfocus)?>,
      coordTextFocus = <?=json_encode($coordTextFocus)?>,
      coordTextUnfocus = <?=json_encode($coordTextUnfocus)?>,
      fontSizeTextFocus = <?=$fontSizeTextFocus?>,
      fontSizeTextUnfocus = <?=$fontSizeTextUnfocus?>,
      themes = <?=json_encode($themesPrincipaux)?>;
  </script>
</body>
</html>
