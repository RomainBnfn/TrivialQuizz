<?php
    try
    {
        $bdd = new PDO(
            "mysql:host=localhost;dbname=mymovies_bfn;charset=utf8",
            "consultant",
            "1234",
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
        );
    }
    catch (Exception $e)
    {
        die('Erreur fatale : '. $e->getMessage());
    }
?>
