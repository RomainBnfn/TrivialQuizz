<?php
  // Les edits considérés ne sont que ceux présents dans les paramètres généraux.
  //
  session_start();

  // On regarde si l'utilisateur est bien un admin
  if(!isset($_SESSION['is_admin']))
  {
    exit();
  }

  require_once "../../include/liaisonbdd.php";
  require_once "../../include/functions.php";
  require_once "../include/functions.php";

  if(empty($_POST['id']) || !is_numeric($_POST['id'])
    || empty($_POST['libelle']) || $_POST['libelle'] == "")
  {
    exit();
  }

  $typeQuestion = 1; // Libre
  if(empty($_POST['typeQuestion'])){
    $typeQuestion = 2; // QCM
  }

  $id = $_POST['id'];

  if($typeQuestion==1){
    if(empty($_POST['reponse']) || empty($_POST["reponseID"]) || !is_numeric(empty($_POST["reponseID"]))){
      exit();
    }
    if($_POST["reponseID"]==-1){ //On vient de changer de type -> Il faut supprimer les anciennes réponses QCM
      deleteReponses($bdd, $id);
      //Et on doit insert la réponse dans la bdd
      createReponseLibre($bdd, $id, $_POST['reponse']);
    }
    else//On modifie juste
    {
      editReponseLibre($bdd, $_POST["reponseID"], $_POST['reponse']);
    }
  }
  if($typeQuestion==2){
    for($i = 1; i <=4; i++){
      if(empty($_POST["reponseQCM_N$i"]) || empty($_POST["reponseID$i"])){
        exit();
      }
    }
    if($_POST["reponseID1"]==-1){ //On vient de changer de type -> Il faut supprimer l'ancienne rep libre
      deleteReponses($bdd, $id);
      //Et on doit insert la réponse dans la bdd
      createReponseQCM($bdd, $id, $_POST["reponseQCM_N1"],
                                  $_POST["reponseQCM_N2"],
                                  $_POST["reponseQCM_N3"],
                                  $_POST["reponseQCM_N4"]);
    }
    else{ //On modifie juste
      editReponseQCM($bdd, $_POST["reponseID1"], $_POST["reponseQCM_N1"],
                            $_POST["reponseID2"], $_POST["reponseQCM_N2"],
                            $_POST["reponseID3"], $_POST["reponseQCM_N3"],
                            $_POST["reponseID4"], $_POST["reponseQCM_N4"]){
    }

  }

  //Tout est ok : Modifications !
  editQuestionLib($bdd, $id, $_POST['libelle']);
  echo "ok";
?>
