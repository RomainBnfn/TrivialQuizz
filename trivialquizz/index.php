<?php
  session_start();
  require_once "include/liaisonbdd.php";
  require_once "include/functions.php";

  $th_base = getAllThemesPrincipauxInfos($bdd);
  $th_custom = getAllThemesPersoInfos($bdd);

  $R = 200; //rayon
  $r = 0; // petite marge
  $G = 1.3; // grandissement focus/unfocus
  $L = 2*($r+$G*$R); // hauteur/largeur de la viewBox
  $c = $L/2; // centre de la viewBox
  $fontSizeTextUnfocus = $R/13;
  $fontSizeTextFocus = $R/11;

  $pathFocus = generatePath($r, $G*$R, $c);
  $pathUnfocus = generatePath($r, $R, $c);

  $coordTextFocus = generateCoordText($r, $G*$R, $c);
  $coordTextUnfocus = generateCoordText($r, $R, $c);

  $themesPrincipaux = getAllThemesPrincipauxInfos($bdd);
  $themesCustoms = getAllThemesPersoInfos($bdd);

  $colorTheme = array();
  $descTheme = array();
  $nomTheme = array();
  $i=0;
  foreach ($themesPrincipaux as $theme) {
    $colorTheme[$i] = $theme['couleur'];
    $descTheme[$i] = $theme['desc'];
    $nomTheme[$i++] = $theme['nom'];
  }
  //variable qui permet de revenir à la page où était l'ut avant qu'il se connecte
  $_SESSION["origin"] = "index.php";
?>
<!doctype html>
<html lang="fr">
<head>
  <title>Trivial Quizz</title>
  <?php require_once "include/header.html"?>
  <link rel="stylesheet" type="text/css" href="css/style-index.css">
</head>
<body>
  <?php require_once "include/navbar.php"?>
  <section class="bandeau-principal fond-bleu">
    Accueil
  </section>
  <section class="cadre-global">
    <div class="cadre-central">
      <article class="container">
        <h1 class="titre1">Thèmes classique</h1>
        <div class="center">
            <svg id="roue-theme-classique" viewBox="0 0 <?="$L $L"?>">
              <?php
              $i = 0;
              $numberIdThemeRelation = array();
              foreach ($themesPrincipaux as $theme) {
                $numberIdThemeRelation[$i] = $theme['id']; //array($i -> $id) $i: position dans le cammenbert / $id: clé primaire dans la bdd
              ?>
                <path class="theme<?=$i?>" d="<?=$pathUnfocus[$i]?>" fill="<?=$theme["couleur"]?>"/>
                <text id="th-text<?=$i?>" fill="#fff" x="<?=$coordTextUnfocus[$i][0]?>" y="<?=$coordTextUnfocus[$i][1]?>"><?=$theme["nom"]?></text>
                <path class="bt-theme theme<?=$i?>" d="<?=$pathUnfocus[$i++]?>" fill="#ffffff00" stroke="#ffffff" stroke-width="1" onClick="clicka(<?=$theme['id']?>)"/>
              <?php
              }
              ?>
              </svg>
          </div>
          <p id="th-desc"></p>
          <a href="">
            <button type="button" name="button" class="btn btn-primary btn-block" id="btn-quizz-smartphone">Voir les quizz</button>
          </a>
      </article>
      <?php
      if(!empty($themesCustoms)){
      ?>
      <article class="container">
        <h1 class="titre1">Thèmes personnalisés</h1>
        <div class="card-columns">
        <?php
          foreach ($themesCustoms as $theme) {
        ?>
          <a class="card rippleContainer"  href="quizz-choice.php?theme=<?=$theme['id']?>" style="background-color: <?=$theme['couleur']?>">
            <div class="card-body text-center">
              <div class="card-border">
                <p class="card-text"><?=$theme['nom']?></p>
                <p class="card-text"><?=$theme['desc']?></p>
              </div>
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
      numberIdThemeRelation = <?=json_encode($numberIdThemeRelation)?>,
      colorTheme = <?=json_encode($colorTheme)?>,
      descTheme = <?=json_encode($descTheme)?>,
      nomTheme = <?=json_encode($nomTheme)?>,
      fontSizeTextFocus = <?=$fontSizeTextFocus?>,
      fontSizeTextUnfocus = <?=$fontSizeTextUnfocus?>;
  </script>
  <?php require_once "js/script.html"?>
</body>
</html>
