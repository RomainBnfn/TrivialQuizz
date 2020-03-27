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

  $pathFocus = generatePath($r, $G*$R, $c);
  $pathUnfocus = generatePath($r, $R, $c);

  $coordTextFocus = generateCoordText($r, $G*$R, $c);
  $coordTextUnfocus = generateCoordText($r, $R, $c);

  $descTheme = array();
  for($i=0;$i<count($th_base);$i=$i+1)
  {
    $descTheme[$i] = $th_base[$i]["desc"];
  }

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
              while ($i < count($th_base) || $i < 6) {
              ?>
                <path class="theme<?=$i?>" d="<?=$pathUnfocus[$i]?>" fill="<?=$th_base[$i]["couleur"]?>"/>
                <text id="th-text<?=$i?>" fill="#fff" x="<?=$coordTextUnfocus[$i][0]?>" y="<?=$coordTextUnfocus[$i][1]?>"><?=$th_base[$i]["nom"]?></text>
                <path class="bt-theme theme<?=$i?>" d="<?=$pathUnfocus[$i]?>" fill="#ffffff00" stroke="#000000" stroke-width="1"/>
              <?php
                $i = $i + 1;
              }
              ?>
              </svg>
          </div>
          <p id="th-desc"></p>
      </article>
      <?php
      if(empty($th_custom)){
      ?>
      <article class="container">
        <h1 class="titre1">Thèmes personnalisés</h1>
        <div id="container-th-custom-btn">
        <?php
          for($i=0;$i<5;$i=$i+1){ //$i<count($th_custom)
            ?>
            <a href="#" class="th-custom-btn center" style="background-color: #22448844" >
              <p class="center">Theme custom stylé</p>
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
    var descTheme = <?=json_encode($descTheme)?>;
  </script>
  <?php require_once "js/script.html"?>
</body>
</html>
