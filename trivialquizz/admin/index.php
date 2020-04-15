<?php
  session_start();
  require_once "../include/index_location.php";

  if(!isset($_SESSION['is_admin']))
  {
    header("Location: $index_location/index.php");
    exit();
  }

  header("Location: $index_location/admin/theme.php");
?>
