<?php
//
// -----------------------------------------------------------------------------
//  Ces fonctions lancent des Requêtes SQL :

  /// Essaie de charger un quizz, envoie null s'il n'existe pas.
  /// S'il existe, renvoie un tableau contenant toutes ses infos.
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
  /// S'il existe, renvoie un tableau contenant toutes ses infos.
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
  ///   Renvoie $_QUESTIONS[] qui comporte tous les $_QUESTION[]
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
  ///   Renvoie $_THEMES[] qui comporte tous les $_THEME[]
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
  ///   Renvoie $_QUIZZES[] qui comporte tous les $_QUIZZ[]
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
?>
