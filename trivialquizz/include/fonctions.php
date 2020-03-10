<?php
  function getDb(){
    $server = "localhost";
    $db = "id12662519_trivial";
    $username = "quizz_superadmin";
    $password = "trivial753";

    return new PDO("mysql:host=$server;dbname=$db;charset=utf8", "$username", "$password",
    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  }

  //$r marge entre les "part", $R rayon d'une "part", $c centre de la roue/"du gateau"
  function generatePath($r, $R, $c){
    $A = array( $c-$r*cos(toRad(60)), $c-$r*sin(toRad(60)));
    $B = array( $c+$r*cos(toRad(60)), $A[1]);
    $C = array( $c+$r, $c);
    $D = array( $B[0], $c+$r*sin(toRad(60)));
    $E = array( $A[0], $D[1]);
    $F = array( $c-$r, $c);

    $a1x = $A[0]-$R*cos(toRad(30)); $a1y = $A[1]-$R*sin(toRad(30));
    $a2y = $A[1]-$R;
    $b2x = $B[0]+$R*cos(toRad(30));
    $c1x = $C[0]+$R*cos(toRad(30)); $c1y = $C[1]-$R*sin(toRad(30));
    $c2y = $C[1]+$R*sin(toRad(30));
    $d1y = $D[1]+$R*sin(toRad(30));
    $d2y = $D[1]+$R;
    $f1x = $F[0]-$R*cos(toRad(30));

    $l = $R+$r;

    $path = array();
    $path[0] = "M $a1x,$a1y A $l,$l 0 0 1 $A[0],$a2y L $A[0],$A[1] Z";
    $path[1] = "M $B[0],$a2y A $l,$l 0 0 1 $b2x,$a1y L $B[0],$B[1] Z";
    $path[2] = "M $c1x,$c1y A $l,$l 0 0 1 $c1x,$c2y L $C[0],$C[1] Z";
    $path[3] = "M $b2x,$d1y A $l,$l 0 0 1 $B[0],$d2y L $D[0],$D[1] Z";
    $path[4] = "M $A[0],$d2y A $l,$l 0 0 1 $a1x,$d1y L $E[0],$E[1] Z";
    $path[5] = "M $f1x,$c2y A $l,$l 0 0 1 $f1x,$c1y L $F[0],$F[1] Z";

    return $path;
  }

  function generateCoordText($r, $R, $c){
    return
    array(
      array($c-0.9*$R*cos(toRad(34)),$c-0.9*$R*sin(toRad(37))),
      array($c+0.7*$R*cos(torad(80)),$c-0.9*$R*sin(toRad(37))),
      array($c+0.2*$R,$c+0.03*$R),
      array($c+0.7*$R*cos(torad(85)),$c+0.9*$R*sin(toRad(40))),
      array($c-0.9*$R*cos(toRad(33)),$c+0.9*$R*sin(toRad(40))),
      array($c-0.9*$R,$c+0.03*$R)
    );
  }

  function toRad($deg){
    return $deg*pi()/180;
  }


?>
