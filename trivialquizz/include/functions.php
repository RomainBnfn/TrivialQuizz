<?php
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
