<?php
  function getBd()
  {
    $server = "localhost";
    $bd = "id12662519_trivial";
    $username = "quizz_superadmin";
    $password = "trivial753";

    return new PDO("mysql:host=$server;dbname=$db;charset=utf8", "$username", "$password",
    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  }
?>
 s
