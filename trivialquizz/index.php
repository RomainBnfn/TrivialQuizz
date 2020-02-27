<!doctype html>
<html lang="fr">
<head>
  <title>Trivial Quizz</title>
  <?php require_once "include/header.html"?>
</head>
<body>
  <?php require_once "include/navbar.php"?>
  <div class="bandeau-principal fond-bleu">Page d'accueil</div>
  <div class="cadre-global">
    <div class="cadre-central">
      <div><h1>Page d'accueil</h1></div>
      <p id="id">Test</p><div>Test</div><div>Test</div>
    </div>
  </div>
  </div>

  <script>
    jQuery(function(){
      $("#id").hover(function(){
          !$(this).append('Hello World !'); // $(this) représente le paragraphe courant
      });
    });
  </script>
  <?php require_once "include/script.html"?>
</body>
</html>
