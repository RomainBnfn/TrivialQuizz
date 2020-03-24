<?php
//
// -----------------------------------------------------------------------------
//  Ces fonctions lancent des Requêtes SQL :

  function tryQueryBDD($bdd, $sql)
  {
    try {
      $request = $bdd -> query($sql);
      $result = $request -> fetchAll();

      if(empty($result)) {
        return null;
      }
      else {
        return $result;
      }
    } catch (\Exception $e) {
      return null;
    }
  }

  /// Essaie de charger un quizz, envoie null s'il n'existe pas.
  /// S'il existe, renvoie un tableau contenant toutes ses infos (id, nom,desc,id_theme).
  function tryLoadQuizz($bdd, $idQuizz)
  {
    $data = tryQueryBDD($bdd, "SELECT * FROM quiz WHERE qui_id = $idQuizz");

    if(!is_null($data)){

      $_QUIZZ;
      $_QUIZZ["id"] = $idQuizz;
      $_QUIZZ["nom"] = $data["qui_nom"];
      $_QUIZZ["desc"] = $data["qui_desc"];
      $_QUIZZ["id_theme"] = $data["th_id"];

      return $_QUIZZ;
    }else {
      return null;
    }
  }

  /// Essaie de charger un theme, envoie null s'il n'existe pas.
  /// S'il existe, renvoie un tableau contenant toutes ses infos (id, nom, couleur).
  function tryLoadTheme($bdd, $idTheme)
  {
    $data = tryQueryBDD($bdd, "SELECT * FROM theme WHERE th_id = $idTheme");

    if(!is_null($data)){

      $_THEME;
      $_THEME["id"] = $idTheme;
      $_THEME["nom"] = $data["th_nom"];
      $_THEME["couleur"] = $data["th_couleur"];

      return $_THEME;
    }else {
      return null;
    }
  }

  /// Essaie de charger toutes les questions d'un quizz, puis renvoie une liste
  /// de liste qui comportent les infos des questions. Renvoie null sinon.
  /// Renvoie $_QUESTIONS[] qui comporte tous les $_QUESTION[] (id, lib, id_bonneRep)
  function tryLoadQuizzQuestion($bdd, $idQuizz)
  {
    if(!is_numeric($idQuizz)) {
      return null;
    }

    $data = tryQueryBDD($bdd, "SELECT * FROM question WHERE que_id IN ( SELECT qui_id FROM quiz_quest WHERE que_id = $idQuizz)");

    if(!is_null($data)){

      $_QUESTIONS[] = [];
      for($i = 0; $i<count($data); $i++) {
        $_QUESTIONS[$i] = array("id" => $data["que_id"], "lib" => $data["que_lib"], "id_bonneRep" => $data["re_id_bonnerep"]);
      }

      //TODO: test utilité
      if (empty($_QUESTIONS[0]))
      {
        return null;
      }
      return $_QUESTIONS;
    }else {
      return null;
    }
  }

  /// Essaie de charger tous les thèmes, puis renvoie une liste de listes
  /// qui comportent les infos des thèmes. Renvoie null sinon.
  ///   Renvoie $_THEMES[] qui comporte tous les $_THEME[] (id, nom, couleur, desc, is_Principal)
  function getAllThemesInfos($bdd)
  {
    $data = tryQueryBDD($bdd, "SELECT * FROM theme");
    return themeTabFormat($data);
  }

  /// Essaie de charger tous les thèmes principaux, puis renvoie une liste de listes
  /// qui comportent les infos des thèmes. Renvoie null sinon.
  ///   Renvoie $_THEMES[] qui comporte tous les $_THEME[] (id, nom, couleur, desc, is_Principal)
  function getAllThemesPrincipauxInfos($bdd)
  {
    $data = tryQueryBDD($bdd, "SELECT * FROM theme WHERE th_is_principal IS NOT NULL");
    return themeTabFormat($data);
  }

  /// Essaie de charger tous les thèmes personalisés, puis renvoie une liste de listes
  /// qui comportent les infos des thèmes. Renvoie null sinon.
  ///   Renvoie $_THEMES[] qui comporte tous les $_THEME[] (id, nom, couleur, desc, is_Principal)
  function getAllThemesPersoInfos($bdd)
  {
    $data = tryQueryBDD($bdd, "SELECT * FROM theme WHERE th_is_principal IS NULL");
    return themeTabFormat($data);
  }

  /// Récupère des données d'une requete sql sur la table theme et les met dans un tableau
  ///   Renvoie $_THEMES[] qui comporte tous les $_THEME[] (id, nom, couleur, desc, is_Principal)
  function themeTabFormat($data){
    if(!is_null($data)) {

      $_THEMES[] = [];
      for($i = 0; $i<count($data); $i++){
        $_THEMES[$i] = array("id" => $data[$i]["th_id"], "nom" => $data[$i]["th_nom"], "desc" => $data[$i]["th_description"], "couleur" => $data[$i]["th_couleur"]);
      }
      if (empty($_THEMES[0]))
      {
        return null;
      }
      return $_THEMES;
    }else {
      return null;
    }
  }

  /// Essaie de charger tous les quizzes, puis renvoie une liste de listes
  /// qui comportent les infos des thèmes. Renvoie null sinon.
  ///   Renvoie $_QUIZZES[] qui comporte tous les $_QUIZZ[] (id, nom, desc, id_theme)
  function getAllQuizzesInfos($bdd)
  {
    $data = tryQueryBDD($bdd, "SELECT * FROM quiz ORDER BY th_id");

    if(!is_null($data)){

      $_QUIZZES[] = [];
      for($i = 0; $i<count($data); $i++) {
        $_QUIZZES[$i] = array('id' => $data["qui_id"], 'nom' => $data["qui_nom"], 'desc' => $data["qui_desc"], 'id_theme' => $data["th_id"]);
      }
      if (empty($_QUIZZES[0]))
      {
        return null;
      }
      return $_QUIZZES;
    }else {
      return null;
    }
  }

  function getAllQuizzNames($bdd)
  {
    $data = tryQueryBDD($bdd, "SELECT qui_nom FROM quiz");

    if(!is_null($data)){

      $_NOMS[] = [];
      for($i = 0; $i<count($data); $i++) {
        $_NOMS[$i] = $data["qui_nom"];
      }
      return $_NOMS;
    }else {
      return null;
    }
  }

  function getNbQuizz($bdd)
  {
    $requete = $bdd -> query("SELECT COUNT(*) FROM quiz");
    $result = $requete -> fetch();
    return $result[0];
  }

  function getNbTheme($bdd)
  {
    $requete = $bdd -> query("SELECT COUNT(*) FROM theme");
    $result = $requete -> fetch();
    return $result[0];
  }

  function existQuizz($bdd, $id)
  {
    $requete = $bdd -> query("SELECT * FROM quiz WHERE qui_id = $id");
    $result = $requete -> fetch();
    return (!empty($result));
  }

  function existTheme($bdd, $id)
  {
    $requete = $bdd -> query("SELECT * FROM theme WHERE th_id = $id");
    $result = $requete -> fetch();
    return (!empty($result));
  }


  // -------------------------------------------------------------------
  //  fonctions pour la construction du camembert des thèmes sur la page d'Accueil:


  //renvoie les path svg du camembert tableau de dim 6 (6 parts)
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

  //Renvoie un tableau de coordonnées (X Y) de position des titres sur le camembert des themes
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

<<<<<<< HEAD
  function getAllThemesNames($bdd)
  {
    $requete = $bdd -> query("SELECT th_nom FROM theme");
    $result = $requete -> fetchAll();

    $listeNoms[] = [];
    $i = 0;
    foreach ($result as $info)
    {
      $listeNoms[$i] = $info["th_nom"];
      $i++;
    }
    return $listeNoms;
  }
=======
>>>>>>> 01c6d0109fa1704f7170b5425b27b78f5e216193
  // -----------------------------------------------------------------------------
  //  Autre :

  function escape($value)
  {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false);
  }

  function loadThemeFromTab($_THEMES, $id_Theme)
  {
    foreach($_THEMES as $_THEME)
    {
      if($_THEME["id"]==$id_Theme)
      {
        return $_THEME;
      }
    }
    return null;
  }
  //Convertie des degrès en rad
  function toRad($deg){
    return $deg*pi()/180;
  }


?>
