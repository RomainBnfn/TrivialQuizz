<!doctype html>
<html lang="fr">
<head>
  <title>Trivial Quizz</title>
  <?php require_once "include/header.html"?>
</head>
<body>
  <?php require_once "include/navbar.php"?>
  <p id="id">Test</p><div>Test</div><div>Test</div>
  <script>
    $(document).ready(function(){
      $("#id").hover(function(){
          !$(this).append('Hello World !'); // $(this) représente le paragraphe courant

      });

    });
  </script>
  <?php require_once "include/script.html"?>
</body>
</html>
