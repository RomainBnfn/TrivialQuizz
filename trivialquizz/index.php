<?php
  session_start();
  include "include/fonctions.php";
  $th_base = (getDb()->query('select * from theme where th_is_principal is not null'))->fetchAll();

  $R = 200; //rayon
  $r = 10; // petite marge
  $G = 1.5; // grandissement focus/unfocus
  $L = 2*($r+$G*$R); // hauteur/largeur de la viewBox
  $c = $L/2; // centre de la viewBox

  $pathFocus = generatePath($r, $G*$R, $c);
  $pathUnfocus = generatePath($r, $R, $c);

  $coordText = array(
    array($c-0.95*$R*cos(toRad(40)),$c-0.95*$R*sin(toRad(40))),
    array($c+0.7*$R*cos(torad(80)),$c-0.95*$R*sin(toRad(40))),
    array($c+0.2*$R,$c+0.03*$R),
    array($c+0.7*$R*cos(torad(80)),$c+0.95*$R*sin(toRad(40))),
    array($c-0.95*$R*cos(toRad(40)),$c+0.95*$R*sin(toRad(40))),
    array($c-0.9*$R,$c+0.03*$R));

?>
<!doctype html>
<html lang="fr">
<head>
  <title>Trivial Quizz</title>
  <?php require_once "include/header.html"?>
  <link rel="stylesheet" type="text/css" href="css/style_index.css">
</head>
<body>
  <?php require_once "include/navbar.php"?>
  <section>
    <article class="container">
      <h1>Thèmes classique</h1>
      <div class="center">
          <svg id="roue_theme_classique" viewBox="0 0 <?="$L $L"?>">
            <?php
            $i = 0;
            while ($i < count($th_base) || $i < 6) {
            ?>
              <path class="theme<?=$i?>" d="<?=$pathUnfocus[$i]?>" fill="<?=$th_base[$i]["th_couleur"]?>"/>
              <text id="th-text<?=$i?>" fill="#fff" x="<?=$coordText[$i][0]?>" y="<?=$coordText[$i][1]?>"><?=$th_base[$i]["th_nom"]?></text>
              <path class="bt-theme theme<?=$i?>" d="<?=$pathUnfocus[$i]?>" fill="#ffffff00" stroke="#000000" stroke-width="1"/>
            <?php
              $i = $i + 1;
            }
            ?>
          </svg>
        </div>
    </article>
    <article class="container">
      <h1>Thèmes personnalisés</h1>

    </article>
  </section>
  <script type="text/javascript">
    var themeFocused = -1;
    var pathFocus = <?=json_encode($pathFocus)?>;
    var pathUnfocus = <?=json_encode($pathUnfocus)?>;
    $(document).ready(function(){
      $(".bt-theme").mouseenter(function(){
        focusTheme(getThemeNumber($(this)));
      });
      $(".bt-theme").mouseleave(function(){
        unfocusTheme(getThemeNumber($(this)));
      });
    });
    function getThemeNumber(pathObj){
      return pathObj[0].getAttribute("class")[14];
    }
    function unfocusTheme(themeNumber){
      var path = document.getElementsByClassName("theme"+themeNumber);
      if(themeNumber != -1){
        for (var i=0;i<path.length;i=i+1)
        {
          path[i].setAttribute("d",pathUnfocus[themeNumber]);
        }
      }
    }
    function focusTheme(themeNumber){
      var path = document.getElementsByClassName("theme"+themeNumber);
      if(themeNumber != -1){
        for (var i=0;i<path.length;i=i+1)
        {
          path[i].setAttribute("d",pathFocus[themeNumber]);
        }
      }
    }
  </script>
  <?php require_once "js/script.html"?>
</body>
</html>
