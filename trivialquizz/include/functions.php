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

  // -----------------------------------------------------------------------------
  //  [ FONCTION UTILITAIRES (A ne pas utiliser directement)
  // -----------------------------------------------------------------------------

  /// Retourne le tableau des infos du Quizz à partir du tableau
  /// de résultats de la requête SQL.
  function loadQuizzFromSQLResult($result)
  {
    $_QUIZZ;
    $_QUIZZ["id"] = $result["qui_id"];
    $_QUIZZ["nom"] = $result["qui_nom"];
    $_QUIZZ["desc"] = $result["qui_desc"];
    $_QUIZZ["temps"] = $result["qui_temps"];
    $_QUIZZ["malus"] = $result["qui_malus"];
    $_QUIZZ["id_theme"] = $result["th_id"];
    return $_QUIZZ;
  }

  /// Retourne le tableau des infos du Thème à partir du tableau
  /// de résultats de la requête SQL.
  function loadThemeFromSQLResult($result)
  {
    $_THEME;
    $_THEME["id"] = $result["th_id"];
    $_THEME["nom"] = $result["th_nom"];
    $_THEME["desc"] = $result["th_description"];
    $_THEME["couleur"] = $result["th_couleur"];
    $_THEME["is_Principal"] = $result["th_is_principal"];
    return $_THEME;
  }

  /// Retourne le tableau des infos de TOUTES les questions & réponses
  /// associées. Le $data en entré est TOUTE le resultat SQL et non juste
  /// une ligne, comme dans les fonctions précédentes. (Plusieurs réponses)
  function loadQuestionReponseFromSQLResult($data)
  {
    $_QUESTIONS;
    foreach ($data as $result) {

      $id = $result["que_id"];
      if(!isset($_QUESTIONS["$id"])){
        $_QUESTION;
        $_QUESTION["id"] = $result["que_id"];
        $_QUESTION["lib"] = $result["que_lib"];
        $_QUESTION["type"] = $result["que_type"];
        $_QUESTION["order"] = $result["qq_order"];
        $_QUESTION["reponses"] = [];
        $_QUESTIONS["$id"] = $_QUESTION;
      }

      $_REPONSE;
      $_REPONSE["id"] = $result["re_id"]; $idRep = $_REPONSE["id"];
      $_REPONSE["lib"] = $result["re_lib"];
      $_REPONSE["isBonne"] = $result["re_isBonne"];
      //
      $_QUESTIONS["$id"]["reponses"]["$idRep"] = $_REPONSE;
    }
    return $_QUESTIONS;
  }

  /// Récupère des données d'une requete sql, une fonction, et renvoie le tableau
  /// où la fonction a été appliqué à toutes les cases de data.
  function tabFormat($data, $fonction){
    if(is_null($data)){
      return null;
    }
    $_DATA;
    for($i = 0; $i<count($data); $i++)
    {
      $temp = $fonction($data[$i]);
      $id = $temp["id"];
      $_DATA["$id"] = $temp;
    }
    if (empty($_DATA)){
      return null;
    }
    return $_DATA;
  }


// -----------------------------------------------------------------------------
//  [ FONCTION POUR LES QUIZZ & THEMES & QUESTIONS ]
// -----------------------------------------------------------------------------

//  Obtenir les infos sur un seul élément :

  /// Essaie de charger un quizz, envoie null s'il n'existe pas.
  /// S'il existe, renvoie un tableau contenant toutes ses infos (id, nom,desc,id_theme).
  function tryLoadQuizz($bdd, $idQuizz)
  {
    $data = tryQueryBDD($bdd, "SELECT * FROM quiz WHERE qui_id = $idQuizz");
    if($data[0] == null)
    {
      return null;
    }
    return loadQuizzFromSQLResult($data[0]);
  }

  /// Essaie de charger un theme, envoie null s'il n'existe pas.
  /// S'il existe, renvoie un tableau contenant toutes ses infos (id, nom, couleur).
  function tryLoadTheme($bdd, $idTheme)
  {
    $data = tryQueryBDD($bdd, "SELECT * FROM theme WHERE th_id = $idTheme");
    if($data[0] == null)
    {
      return null;
    }
    return loadThemeFromSQLResult($data[0]);
  }



//  Obtenir les infos sur plusieurs éléments :

  // (THEMES)

        /// Essaie de charger tous les thèmes, puis renvoie une liste de listes
        /// qui comportent les infos des thèmes. Renvoie null sinon.
        ///   Renvoie $_THEMES[] qui comporte tous les $_THEME[] (id, nom, couleur, desc, is_Principal)
        function getAllThemesInfos($bdd)
        {
          $data = tryQueryBDD($bdd, "SELECT * FROM theme");
          return tabFormat($data, "loadThemeFromSQLResult");
        }

        /// Essaie de charger tous les thèmes principaux, puis renvoie une liste de listes
        /// qui comportent les infos des thèmes. Renvoie null sinon.
        ///   Renvoie $_THEMES[] qui comporte tous les $_THEME[] (id, nom, couleur, desc, is_Principal)
        function getAllThemesPrincipauxInfos($bdd)
        {
          $data = tryQueryBDD($bdd, "SELECT * FROM theme WHERE th_is_principal = 1");
          return tabFormat($data, "loadThemeFromSQLResult");
        }

        /// Essaie de charger tous les thèmes personalisés, puis renvoie une liste de listes
        /// qui comportent les infos des thèmes. Renvoie null sinon.
        ///   Renvoie $_THEMES[] qui comporte tous les $_THEME[] (id, nom, couleur, desc, is_Principal)
        function getAllThemesPersoInfos($bdd)
        {
          $data = tryQueryBDD($bdd, "SELECT * FROM theme WHERE th_is_principal != 1");
          return tabFormat($data, "loadThemeFromSQLResult");
        }

        function getNumbersOfQuizzesOfThemes($bdd)
        {
          $data = tryQueryBDD($bdd, "SELECT COUNT(*) AS NB, th_id FROM quiz GROUP BY th_id");
          $_NUMBERS;
          if ($data == null)
          {
              return null;
          }
          foreach ($data as $infos)
          {
            $_NUMBERS[$infos["th_id"]] = $infos["NB"];
          }
          return $_NUMBERS;
        }

        function getNumbersOfQuizzesWithQuestionsOfThemes($bdd)
        {
          $data = tryQueryBDD($bdd, "SELECT COUNT(*) AS NB, th_id FROM quiz WHERE qui_id IN (SELECT qui_id FROM quiz_quest GROUP BY qui_id HAVING COUNT(qui_id) > 0) GROUP BY th_id ");
          $_NUMBERS;
          if ($data == null)
          {
              return null;
          }
          foreach ($data as $infos)
          {
            $_NUMBERS[$infos["th_id"]] = $infos["NB"];
          }
          return $_NUMBERS;
        }

  // (QUIZZES)

        /// Essaie de charger tous les quizzes, puis renvoie une liste de listes
        /// qui comportent les infos des thèmes. Renvoie null sinon.
        ///   Renvoie $_QUIZZES[] qui comporte tous les $_QUIZZ[] (id, nom, desc, id_theme)
        function getAllQuizzesInfos($bdd)
        {
          $data = tryQueryBDD($bdd, "SELECT * FROM quiz ORDER BY th_id");
          return tabFormat($data, "loadQuizzFromSQLResult");
        }

        /// Essaie de charger tous les quizzes d'un thème particulier,
        /// puis renvoie une liste de listes qui comportent les infos des quizzes. Renvoie null sinon.
        ///   Renvoie $_QUIZZES[] qui comporte tous les $_QUIZZ[] (id, nom, desc, id_theme)
        function getAllQuizzesInfosOfTheme($bdd, $idTheme)
        {
          $data = tryQueryBDD($bdd, "SELECT * FROM quiz WHERE th_id = $idTheme");
          return tabFormat($data, "loadQuizzFromSQLResult");
        }

        ///
        function getAllQuizzNames($bdd)
        {
          $data = tryQueryBDD($bdd, "SELECT qui_nom FROM quiz");

          if(!is_null($data)){

            $_NOMS[] = [];
            for($i = 0; $i<count($data); $i++) {
              $_NOMS[$i] = $data[$i]["qui_nom"];
            }
            return $_NOMS;
          }else {
            return null;
          }
        }

        function getNumbersOfQuestionsOfAllQuizzes($bdd)
        {
          $data = tryQueryBDD($bdd, "SELECT COUNT(*) AS NB, qui_id FROM quiz_quest GROUP BY qui_id");
          $_NUMBERS;
          if ($data == null)
          {
              return null;
          }
          foreach ($data as $infos)
          {
            $_NUMBERS[$infos["qui_id"]] = $infos["NB"];
          }
          return $_NUMBERS;
        }

        function getNumbersOfQuestionsOfQuizzes($bdd, $idTheme)
        {
          $data = tryQueryBDD($bdd, "SELECT COUNT(*) AS NB, qui_id FROM quiz_quest WHERE qui_id IN (SELECT qui_id FROM quiz WHERE th_id = $idTheme) GROUP BY qui_id");
          $_NUMBERS;
          if ($data == null)
          {
              return null;
          }
          foreach ($data as $infos)
          {
            $_NUMBERS[$infos["qui_id"]] = $infos["NB"];
          }
          return $_NUMBERS;
        }

        function getAllQuizzesDuration($bdd, $idTheme){
          $data = tryQueryBDD($bdd, "SELECT qui_id, qui_temps, qui_malus FROM quiz WHERE th_id=$idTheme");
          if ($data == null)
          {
              return null;
          }
          $_DURATIONS;
          foreach ($data as $infos)
          {
            $_DURATIONS[$infos["qui_id"]] = array("temps" => $infos["qui_temps"],
                                                  "malus" => $infos["qui_malus"]);
          }
          return $_DURATIONS;
        }

  // (QUESTIONS)
        /// Essaie de charger toutes les questions d'un quizz, puis renvoie une liste
        /// de liste qui comportent les infos des questions. Renvoie null sinon.
        /// Renvoie $_QUESTIONS[] qui comporte tous les $_QUESTION[] (id, lib, id_bonneRep)
        function tryLoadQuizzQuestion($bdd, $idQuizz)
        {
          if(!is_numeric($idQuizz)) {
            return null;
          }
          $data = tryQueryBDD($bdd, "SELECT DISTINCT * FROM question, reponse, quiz_quest
                                                       WHERE quiz_quest.qui_id = $idQuizz
                                                       AND question.que_id = reponse.que_id
                                                       AND question.que_id = quiz_quest.que_id
                                                       ORDER BY qq_order, question.que_id;");
          if(empty($data)){
            return null;
          }
          return loadQuestionReponseFromSQLResult($data);
        }

        function getAllNbAssociationQuestion($bdd, $idQuizz)
        {
          $data = tryQueryBDD($bdd, "SELECT que_id, COUNT(que_id) as nb FROM quiz_quest GROUP BY que_id HAVING que_id IN (SELECT que_id FROM quiz_quest WHERE qui_id = $idQuizz)");
          if(is_null($data)){
            return null;
          }
          $_NBASSOCIATION;
          foreach ($data as $infos) {
            $id = $infos['que_id'];
            $_NBASSOCIATION["$id"] = $infos["nb"];
          }
          return $_NBASSOCIATION;
        }


  // (SCORE)

        function getScoreGlobaux($bdd, $idTheme){
          $dataId = tryQueryBDD($bdd, "SELECT DISTINCT qui_id FROM quiz WHERE th_id=$idTheme");
          $_SCORE;
          if(is_null($dataId)){
            return null;
          }
          foreach ($dataId as $id) {
            if(!is_null($id)){
              $idQuizz = $id['qui_id'];
              $data = tryQueryBDD($bdd, "SELECT sc_point, sc_temps, qui_id FROM score WHERE qui_id=$idQuizz ORDER BY sc_point desc, sc_temps LIMIT 1");
              if(!is_null($data)){
                $_SCORE[$data[0]['qui_id']] = array("point" => $data[0]['sc_point'],
                                                      "temps" => $data[0]['sc_temps']);
              }else{
                $_SCORE[$idQuizz] = array("point" => -1,
                                      "temps" => -1);
              }
            }
          }
          return $_SCORE;
        }

        function getScorePerso($bdd, $idTheme, $pseudo){
          $dataId = tryQueryBDD($bdd, "SELECT DISTINCT qui_id FROM quiz WHERE th_id=$idTheme");
          $_SCORE;
          if(is_null($dataId)){
            return null;
          }
          foreach ($dataId as $id) {
            if(!is_null($id)){
              $idQuizz = $id['qui_id'];
              $data = tryQueryBDD($bdd, "SELECT sc_point, sc_temps, qui_id FROM score WHERE qui_id=$idQuizz AND pr_pseudo='$pseudo' ORDER BY sc_point desc, sc_temps LIMIT 1");
              if(!is_null($data)){
                $_SCORE[$data[0]['qui_id']] = array("point" => $data[0]['sc_point'],
                                                    "temps" => $data[0]['sc_temps']);
              }else{
                $_SCORE[$idQuizz] = array("point" => -1,
                                    "temps" => -1);
              }
            }
          }
          return $_SCORE;
        }

        function getStatistique($bdd, $idQuizz){
          if(!is_numeric($idQuizz)){
            return;
          }
          $data = tryQueryBDD($bdd, "SELECT COUNT(*) as nbFois, AVG(sc_difficulte) as difMoy, AVG(sc_point) as scoMoy, AVG(sc_temps) as tempMoy FROM score WHERE qui_id = $idQuizz");
          $_STATS;
          if(is_null($data)){
            $_STATS["score"] = 0;
            $_STATS["temps"] = 0;
            $_STATS["difficulte"] = 0;
            $_STATS["nb"] = 0;
            return $_STATS;
          }
          $info = $data[0];
          $_STATS["score"] = $info["scoMoy"];
          $_STATS["temps"] = $info["tempMoy"];
          $_STATS["difficulte"] = $info["difMoy"];
          $_STATS["nb"] = $info["nbFois"];
          return $_STATS;
        }

// Get Nb
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

// Exists
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

  function existQuestion($bdd, $id)
  {
    $requete = $bdd -> query("SELECT * FROM question WHERE que_id = $id");
    $result = $requete -> fetch();
    return (!empty($result));
  }

//
//
//

  function getAllPseudo($bdd){
    $dataId = tryQueryBDD($bdd, "SELECT pr_pseudo FROM profil");
    if(is_null($dataId)){
      return null;
    }
    $_PSEUDOS;
    $i = -1;
    foreach ($dataId as $id) {
      $i++;
      $_PSEUDOS[$i] = $id["pr_pseudo"];
    }
    return $_PSEUDOS;
  }

  function timeToString($time){
    $min = ($time  - $time  % 60) / 60;
    $sec = $time  % 60;
    if($sec<10)
      return $min."m0".$sec."s";
    else if($min>0)
      return $min."m".$sec."s";
    else
      return $sec."s";
  }

  function existNotEmptyThemeCustom($_NBOFQUIZZOFTHEME){
    foreach ($_NBOFQUIZZOFTHEME as $id => $value) {
      if($id > 6){
        return true;
      }
    }
    return false;
  }
// -----------------------------------------------------------------------------
//  Fonctions pour la construction du camembert des thèmes sur la page d'Accueil:
// -----------------------------------------------------------------------------

  //renvoie les path svg du camembert tableau de dim 6 (6 parts)
  //$r marge entre les "part", $R rayon d'une "part", $c centre de la roue/"du gateau"
  function generatePath($r, $R, $c, $ids){
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
    $path[$ids[0]] = "M $a1x,$a1y A $l,$l 0 0 1 $A[0],$a2y L $A[0],$A[1] Z";
    $path[$ids[1]] = "M $B[0],$a2y A $l,$l 0 0 1 $b2x,$a1y L $B[0],$B[1] Z";
    $path[$ids[2]] = "M $c1x,$c1y A $l,$l 0 0 1 $c1x,$c2y L $C[0],$C[1] Z";
    $path[$ids[3]] = "M $b2x,$d1y A $l,$l 0 0 1 $B[0],$d2y L $D[0],$D[1] Z";
    $path[$ids[4]] = "M $A[0],$d2y A $l,$l 0 0 1 $a1x,$d1y L $E[0],$E[1] Z";
    $path[$ids[5]] = "M $f1x,$c2y A $l,$l 0 0 1 $f1x,$c1y L $F[0],$F[1] Z";

    return $path;
  }

  //Renvoie un tableau de coordonnées (X Y) de position des titres sur le camembert des themes
  function generateCoordText($r, $R, $c, $ids){
    return
    array(
      $ids[0] => array($c-0.9*$R*cos(toRad(34)),$c-0.9*$R*sin(toRad(37))),
      $ids[1] => array($c+0.7*$R*cos(torad(80)),$c-0.9*$R*sin(toRad(37))),
      $ids[2] => array($c+0.2*$R,$c+0.03*$R),
      $ids[3] => array($c+0.7*$R*cos(torad(85)),$c+0.9*$R*sin(toRad(40))),
      $ids[4] => array($c-0.9*$R*cos(toRad(33)),$c+0.9*$R*sin(toRad(40))),
      $ids[5] => array($c-0.9*$R,$c+0.03*$R)
    );
  }

//HEAD
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
//=======
//>>>>>>> 01c6d0109fa1704f7170b5425b27b78f5e216193
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
