<?php
//
// -----------------------------------------------------------------------------
//  Ces fonctions lancent des Requêtes SQL :

  /// Essaie de charger un quizz, envoie null s'il n'existe pas.
  /// S'il existe, renvoie un tableau contenant toutes ses infos (id, nom,desc,id_theme).
  function tryLoadQuizz($bdd, $idQuizz)
  {
    try
    {
      if(!is_numeric($idQuizz))
      {
        return null;
      }

      $requete = $bdd -> query("SELECT * FROM quiz WHERE qui_id = $idQuizz");
      $result = $requete -> fetch();

      // Le Quizz n'existe pas !
      if(empty($result))
      {
        return null;
      }

      $_QUIZZ;
      $_QUIZZ["id"] = $idQuizz;
      $_QUIZZ["nom"] = $result["qui_nom"];
      $_QUIZZ["desc"] = $result["qui_desc"];
      $_QUIZZ["id_theme"] = $result["th_id"];
      //Suite is Comming

      return $_QUIZZ;
    }
    catch (\Exception $e)
    {
      return null;
    }
  }

  /// Essaie de charger un theme, envoie null s'il n'existe pas.
  /// S'il existe, renvoie un tableau contenant toutes ses infos (id, nom, couleur).
  function tryLoadTheme($bdd, $idTheme)
  {
    try
    {
      if(!is_numeric($idTheme))
      {
        return null;
      }
      $requete = $bdd -> query("SELECT * FROM theme WHERE th_id = $idTheme");
      $result = $requete -> fetch();

      if(empty($result))
      {
        return null;
      }

      $_THEME;
      $_THEME["id"] = $idTheme;
      $_THEME["nom"] = $result["th_nom"];
      $_THEME["couleur"] = $result["th_couleur"];
      //Suite is Comming

      return $_THEME;
    }
    catch (\Exception $e)
    {
      return null;
    }
  }

  /// Essaie de charger toutes les questions d'un quizz, puis renvoie une liste
  /// de liste qui comportent les infos des questions. Renvoie null sinon.
  /// Renvoie $_QUESTIONS[] qui comporte tous les $_QUESTION[] (id, lib, id_bonneRep)
  function tryLoadQuizzQuestion($bdd, $idQuizz)
  {
    try
    {
      if(!is_numeric($idQuizz))
      {
        return null;
      }

      $requete = $bdd -> query(
        "SELECT * FROM question
         WHERE que_id IN
         ( SELECT qui_id FROM quiz_quest
           WHERE que_id = $idQuizz)"
         );

      $_QUESTIONS[] = [];
      $i = 0;
      while ($result = $requete -> fetch())
      {
        $_QUESTIONS[$i] = array("id" => result["que_id"], "lib" => result["que_lib"], "id_bonneRep" =>result["re_id_bonnerep"]);
        $i++;
      }
      if (empty($_QUESTIONS[0]))
      {
        return null;
      }
      return $_QUESTIONS;
    }
    catch (\Exception $e)
    {
      return null;
    }
  }

  /// Essaie de charger tous les thèmes, puis renvoie une liste de listes
  /// qui comportent les infos des thèmes. Renvoie null sinon.
  ///   Renvoie $_THEMES[] qui comporte tous les $_THEME[] (id, nom, couleur, desc)
  function getAllThemesInfos($bdd)
  {
    $requete = $bdd -> query("SELECT * FROM theme");
    $result = $requete -> fetchAll();

    if(empty($result))
    {
      return null;
    }

    $_THEMES[] = [];
    $i = 0;
    foreach ($result as $info)
    {
      $_THEMES[$i] = array('id' => $info["th_id"], 'nom' => $info["th_nom"], 'couleur' => $info["th_couleur"], 'desc' => $info["th_description"], 'is_Principal' => $info["th_is_principal"]);
      $i++;
    }
    if (empty($_THEMES[0]))
    {
      return null;
    }
    return $_THEMES;
  }

  /// Essaie de charger tous les quizzes, puis renvoie une liste de listes
  /// qui comportent les infos des thèmes. Renvoie null sinon.
  ///   Renvoie $_QUIZZES[] qui comporte tous les $_QUIZZ[] (id, nom, desc, id_theme)
  function getAllQuizzesInfos($bdd)
  {
    $requete = $bdd -> query("SELECT * FROM quiz ORDER BY th_id");
    $result = $requete -> fetchAll();

    if(empty($result))
    {
      return null;
    }

    $_QUIZZES[] = [];
    $i = 0;
    foreach ($result as $info)
    {
      $_QUIZZES[$i] = array('id' => $info["qui_id"], 'nom' => $info["qui_nom"], 'desc' => $info["qui_desc"], 'id_theme' => $info["th_id"]);
      $i++;
    }
    if (empty($_QUIZZES[0]))
    {
      return null;
    }
    return $_QUIZZES;
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

  function getAllQuizzNames($bdd)
  {
    $requete = $bdd -> query("SELECT qui_nom FROM quiz");
    $result = $requete -> fetchAll();

    $listeNoms[] = [];
    $i = 0;
    foreach ($result as $info)
    {
      $listeNoms[$i] = $info["qui_nom"];
      $i++;
    }
    return $listeNoms;
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
