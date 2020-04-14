<?php

require_once "../include/liaisonbdd.php";
require_once "../include/functions.php";

if(isset($_GET['point']) &&
  isset($_GET['temps']) &&
  isset($_GET['diff']) &&
  isset($_GET['profil']) &&
  isset($_GET['quizz'])){

  $requeste = $bdd -> prepare("INSERT INTO score ( sc_point, sc_temps, sc_difficulte, sc_date, pr_pseudo, qui_id) VALUES ( ?, ?, ?, ?, ?, ?)");

  $requeste -> execute( array(
      escape($_GET['point']),
      escape($_GET['temps']),
      escape($_GET['diff']),
      date("Y-m-d"),
      escape($_GET['profil']),
      escape($_GET['quizz'])));

}
?>
