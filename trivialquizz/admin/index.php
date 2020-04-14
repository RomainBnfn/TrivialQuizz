<?php
  session_start();

  //TODO: Changer ça
  $base_location = "/trivial/trivialquizz";

  if(!isset($_SESSION['is_admin']))
  {
    //TODO: Changer la location
    header("Location: $base_location/index.php");
    exit();
  }

  header("Location: $base_location/admin/theme.php");
?>
