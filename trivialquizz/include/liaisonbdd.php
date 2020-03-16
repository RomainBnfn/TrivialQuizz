<?php
    try
    {
        $bdd = new PDO(
            "mysql:host=localhost;dbname=id12662519_trivial;charset=utf8",
            "quizz_superadmin",
            "trivial753",
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
        );
    }
    catch (Exception $e)
    {
        die('Erreur fatale : '. $e->getMessage());
    }
?>
