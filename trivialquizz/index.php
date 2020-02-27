<!doctype html>
<html lang="fr">
<head>
  <title>Trivial Quizz</title>
  <?php require_once "include/header.html"?>
  <?php require_once "include/script.html"?>
</head>
<body>
  <?php require_once "include/navbar.php"?>
  <p id="id">Test</p><div>Test</div><div>Test</div>
  <script>
    jQuery(function(){
      $("#id").hover(function(){
          !$(this).append('Hello World !'); // $(this) représente le paragraphe courant

      });

    });
  </script>
</body>
</html>
