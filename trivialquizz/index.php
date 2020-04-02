<?php
  session_start();
  require_once "include/liaisonbdd.php";
  require_once "include/functions.php";

  $th_base = getAllThemesPrincipauxInfos($bdd);
  $th_custom = getAllThemesPersoInfos($bdd);

  $R = 200; //rayon
  $r = 5; // petite marge
  $G = 1.2; // grandissement focus/unfocus
  $L = 2*($r+$G*$R); // hauteur/largeur de la viewBox
  $c = $L/2; // centre de la viewBox
  $fontSizeTextUnfocus = $R/13;
  $fontSizeTextFocus = $R/11;

  $pathFocus = generatePath($r, $G*$R, $c);
  $pathUnfocus = generatePath($r, $R, $c);

  $coordTextFocus = generateCoordText($r, $G*$R, $c);
  $coordTextUnfocus = generateCoordText($r, $R, $c);

  $descTheme = array();
  for($i=0;$i<count($th_base);$i=$i+1)
  {
    $descTheme[$i] = $th_base[$i]["desc"];
  }
  $colorTheme = array();
  for($i=0;$i<count($th_base);$i=$i+1)
  {
    $colorTheme[$i] = $th_base[$i]["couleur"];
  }
  $nomTheme = array();
  for($i=0;$i<count($th_base);$i=$i+1)
  {
    $nomTheme[$i] = $th_base[$i]["nom"];
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
              while ($i < count($th_base) || $i < 6) {
                $numberIdThemeRelation[$i] = $th_base[$i]['id'];
              ?>
                <path class="theme<?=$i?>" d="<?=$pathUnfocus[$i]?>" fill="<?=$th_base[$i]["couleur"]?>"/>
                <text id="th-text<?=$i?>" fill="#fff" x="<?=$coordTextUnfocus[$i][0]?>" y="<?=$coordTextUnfocus[$i][1]?>" style="font-size: <?=$fontSizeTextUnfocus?>px"><?=$th_base[$i]["nom"]?></text>
                <path class="bt-theme theme<?=$i?>" d="<?=$pathUnfocus[$i]?>" fill="#ffffff00" stroke="#000000" stroke-width="1"/>
              <?php
                $i = $i + 1;
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
        if(!empty($th_custom)){
      ?>
      <article class="container">
        <h1 class="titre1">Thèmes personnalisés</h1>
        <div class="card-columns">
        <?php
          for($i=0;$i<count($th_custom);$i=$i+1){
            ?>
              <a class="card" href="quizz-choice.php?theme=<?=$th_custom[$i]['id']?>" style="background-color: <?=$th_custom[$i]['couleur']?>">
                <div class="card-body text-center">
                  <p class="card-text"><?=$th_custom[$i]['nom']?></p>
                  <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.<?=$th_custom[$i]['desc']?></p>
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
  <script type="text/javascript">
    var pathFocus = <?=json_encode($pathFocus)?>;
    var pathUnfocus = <?=json_encode($pathUnfocus)?>;
    var coordTextFocus = <?=json_encode($coordTextFocus)?>;
    var coordTextUnfocus = <?=json_encode($coordTextUnfocus)?>;
    var numberIdThemeRelation = <?=json_encode($numberIdThemeRelation)?>;
    var colorTheme = <?=json_encode($colorTheme)?>;
    var descTheme = <?=json_encode($descTheme)?>;
    var nomTheme = <?=json_encode($nomTheme)?>;
    var fontSizeTextFocus = <?=$fontSizeTextFocus?>;
    var fontSizeTextUnfocus = <?=$fontSizeTextUnfocus?>;
  </script>
  <?php require_once "js/script.html"?>
</body>
</html>
