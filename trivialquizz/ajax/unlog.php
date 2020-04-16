<?php
  session_start();

  // On regarde si l'utilisateur est bien un admin
  print_r($_SESSION);
  unset($_SESSION);
  print_r($_SESSION);
?>
