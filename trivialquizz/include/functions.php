<?php
//
// -----------------------------------------------------------------------------
// UPDATE (21/03/2020)

  /// Essaie de charger un quizz, envoie null s'il n'existe pas.
  /// S'il existe, renvoie un tableau contenant toutes ses infos.
  function tryLoadQuizz($bdd, $id)
  {
    try
    {
      if(!is_numeric($id))
      {
        return null;
      }

      $requete = $bdd -> query("SELECT * FROM quiz WHERE qui_id = $id");
      $result = $requete -> fetch();

      // Le Quizz n'existe pas !
      if(empty($result))
      {
        return null;
      }

      $_QUIZZ;
      $_QUIZZ["id"] = $id;
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
  function tryLoadTheme($bdd, $id)
  {
    try
    {
      if(!is_numeric($id))
      {
        return null;
      }
      $requete = $bdd -> query("SELECT * FROM theme WHERE th_id = $id");
      $result = $requete -> fetch();

      if(empty($result))
      {
        return null;
      }

      $_THEME;
      $_THEME["id"] = $id;
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

  ///
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

  ///
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

//
// -----------------------------------------------------------------------------
// OLD

  function escape($value)
  {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false);
  }

  function getAllThemesID($bdd)
  {
    $requete = $bdd -> query("SELECT th_id FROM theme");
    $result = $requete -> fetchAll();
    //
    $listeThemes[] = [];
    $i = 0;
    foreach ($result as $info)
    {
      $listeThemes[$i] = $info["th_id"];
      $i++;
    }
    return $listeThemes;
  }

  function getAllQuizzID($bdd)
  {
    $requete = $bdd -> query("SELECT qui_id FROM quiz");
    $result = $requete -> fetchAll();
    //
    $listeQuizz[] = [];
    $i = 0;
    foreach ($result as $info)
    {
      $listeQuizz[$i] = $info["qui_id"];
      $i++;
    }
    return $listeQuizz;
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

  function getQuizzInfo($bdd, $id)
  {
    $requete = $bdd -> query("SELECT * FROM quiz WHERE qui_id = $id");
    return $requete -> fetch();
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

?>
