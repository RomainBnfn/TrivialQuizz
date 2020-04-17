<?php
  /*  Ceci n'est pas une page.
   */
   session_start();

   // On regarde si l'utilisateur est bien un admin
   if(!isset($_SESSION['is_admin']))
   {
     exit();
   }

   require_once "../../include/liaisonbdd.php";
   require_once "../../include/functions.php";

   // On regarde si l'id passé en méthode get est correct
   if(empty($_GET['id']) || !is_numeric($_GET['id']) )
   {
     exit();
   }

   $id = $_GET['id'];

   // Suppression d'un Thème principal
   $_THEME = tryLoadTheme($bdd, $id);

   if($_THEME == null || $_THEME["is_Principal"] == 1)
   {
     exit();
   }

   // On doit regarder si des Quizz sont associés à ce thème avant de le supprimer
   // Puis les associer au thème 6 (Principal)
   $request = $bdd -> query("SELECT * FROM QUIZ WHERE th_id = $id");
   $result = $request -> fetchAll();

   if(!empty($result)) {
     //On modifie les th_id des Quizz
     $bdd -> query("UPDATE QUIZ SET th_id = 6 WHERE th_id = $id;");
   }

   //Tout est ok : Suppression !
   $bdd -> query("DELETE FROM THEME WHERE th_id = $id");
   echo "ok";
?>
