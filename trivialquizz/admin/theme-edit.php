<?php
  session_start();
  //TODO: Changer la location
  $index_location = "/trivial/trivialquizz/admin/index.php";

  //TODO: Enlever ça
  $_SESSION['is_admin'] = true;
  // Le visiteur n'est pas un admin.
  if(!isset($_SESSION['is_admin']))
  {
    header("Location: ".$index_location);
    exit();
  }

  require_once "../include/liaisonbdd.php";
  require_once "../include/functions.php";

  // Si le id en GET n'existe pas ou n'est pas un numbre.
  if(empty($_GET['id']) || !is_numeric($_GET['id']) )
  {
    header("Location: ".$index_location);
    exit();
  }
  $id = $_GET['id'];
  $requete = $bdd -> prepare("SELECT * FROM theme WHERE th_id = ?");
  $requete -> execute(array($id));
  $result = $requete -> fetch();

  // Le thème n'existe pas.
  if(empty($result))
  {
    header("Location: ".$index_location);
    exit();
  }

  // Tout est ok, le thème existe
  // On ajoute le prefix th car plus tard, "id" , "nom".. seront réutilisés.
  $th_id = $id;
  $th_nom = $result["th_nom"];
  $th_description= $result["th_description"];
?>

<!doctype html>
<html lang="fr">
<head>
  <title>Edition de Theme</title>
  <?php require_once "../include/header.html"?>
</head>
<body>
  <?php require_once "../include/navbar.php"?>

  <!-- TODO: Ajouter une couleur en fct de $couleur -->
  <div class="bandeau-principal fond-bleu">Edition de Thème : <?= $th_nom ?></div>

  <div class="cadre-global">
    <div class="cadre-central">

      <!-- DEBUT : Cadre des options générales -->
      <div>
        <div class="titre1">
          <div>Paramètres Généraux</div>
        </div>
        <div>
          <form id="formGeneral" method="POST" onsubmit="">
            <div>
              <div id="formGeneralNom_error"></div>
              <label name="nom">Nom : </label>
              <input id="formGeneralNom" type="text" name="nom" value="<?= $th_nom ?>" required/>
            </div> <br/>
            <div>
              <div id="formGeneralDesc_error"></div>
              <label name="desc">Description : </label>
              <textarea id="formGeneralDesc" type="text" name="desc" rows="5" draggable="false" required ><?= $th_description ?></textarea>
            </div> <br/>
            <div>
              <div id="formGeneralCouleur_error"></div>
              <label>Couleur : </label>
              <input id="formGeneralCouleur" />
            </div>
            <input type="submit" />
          </form>
        </div>
      </div>
      <!-- FIN : Cadre des options générales -->

      <br/>

      <!-- DEBUT : Cadre de la liste des Questions -->
      <div>
        <div class="titre1">
          <div>Les Quizz associés</div>
        </div>

        <div>
          <?php
            $requete = $bdd -> query("SELECT * FROM quiz WHERE th_id = ".$th_id);
            $result = $requete -> fetchAll();

            if(empty($result))
            {
              echo "Il parrait qu'aucun quizz n'a été associé à ce thème, c'est terrible !";
            }
            else
            {
              foreach ($result as $infos_quizz)
              {
                $infos_quizz["n"]
                ?>
                  <div>
                    <?= $name ?>
                  </div>
                <?php
              }
            }
           ?>
        </div>
      </div>
      <!-- FIN : Cadre de la liste des Questions -->

    </div>
  </div>

  <?php require_once "../include/script.html"?>
  <script>
    $(function(){
      $("#formGeneralNom").on({
        blur : function(){
          if ($(this).val() == "")
          {
            $(this).css({
              borderColor : 'red'
            });
          }
        },

        keyup : function(){
          if ($(this).val() == "")
          {
            $(this).css({
              borderColor : 'red'
            });
          }
          else
          {
            $(this).css({
              borderColor : 'grey'
            });
          }
        }
      });
    });
  </script>
</body>
</html>
