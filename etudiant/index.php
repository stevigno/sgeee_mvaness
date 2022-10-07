<?php
/**
 * Cette page représente le squelette de notre application
 * c'est le support de toutes les sessions de ce panel d'administration
 */

include '../global/init.php';

// Début de la tamporisation de sortie
ob_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>SGEEE - Etudiant</title>

  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link rel="icon" type="../image/png" href="../img/logo-yoolearn.png" />

  <!-- stylesheet -->
  <link href="../css/admin.min.css" rel="stylesheet">
  <link href="../css/styles.css" rel="stylesheet">
  <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

<?php
//include_once 'vues/form_connexion_ens.php';
// Validation d'une évaluation
if(isset($_SESSION['id_etd']) && isset($_POST['ue']) && $_POST['id_etudiant'])
{
  if(evaluation_deja_soumis($_SESSION['id_etd'], $_POST['ue'], $_SESSION['sess_eval']))
  {
    include_once 'vues/evaluation_non_ok.php';
  }
  else
  {
    // Enregistrement d'une évaluation
    $all_quest = liste_question();
    $nb_quest = $all_quest->rowCount();  // nombre total de questions


    /******************* recupération de l'id de l'étudiant et celui de l'ue ***************************/

    $ue = $_POST['ue'];
    $id_etudiant = $_POST['id_etudiant'];

    /******************* evaluation ***************************/

    while ($quest = $all_quest->fetch())
    {
      if(1)
      {
        $id_quest = $quest['quest_id'];
        $id_input_quest = "quest_".$id_quest;
        $val_question = $_POST[$id_input_quest];
        evaluer($id_etudiant,$id_quest,$ue,$val_question,$_SESSION['sess_eval']);

      }
    }

    if(isset($_POST['quest_suggestion']))
    {
      // soumission d'une suggestion
      $content_suggestion = $_POST['quest_suggestion'];
      soumettre_suggestion($id_etudiant, $_SESSION['sess_eval'], $ue, $content_suggestion);
    }
    include_once 'vues/evaluation_ok.php';
  }

}
elseif(isset($_POST['login_etd']) && isset($_POST['mdp_etd']))
{
  $login = $_POST['login_etd'];
  $mdp = $_POST['mdp_etd'];
  if($login !=="" && $mdp!=="")
  {
    $id_user = connexion_etudiant($login, $mdp);
    if($id_user)
    {
      //$info_user = lire_infos_utilisateur($id_user,$table);
      $info_user = lire_infos_utilisateur_etudiant($id_user);
      $_SESSION['id_etd'] = $info_user['id'];
      $_SESSION['nom_etd'] = $info_user['nom'];
      $_SESSION['prenom_etd'] = $info_user['prenom'];
      $_SESSION['niveau_etd'] = $info_user['id_niveau'];
      //echo $_SESSION['id_etd']." ".$_SESSION['nom_etd'];

      // Recupération de la session d'évaluation en cours
      $sess_eval = session_en_cours();
      $_SESSION['sess_eval'] = $sess_eval['id'];
      $_SESSION['sess_eval_mois'] = $sess_eval['mois'];
      $_SESSION['sess_eval_annee'] = $sess_eval['annee'];
      include_once 'vues/content.php';
    }
    else
    {
      header('Location: index.php?erreur=2');
    }
  }
  else
  {
    header('Location: index.php?erreur=1');
  }
}
else
{
  include_once 'vues/form_login.php';
}

?>

<!-- Bootstrap core JavaScript-->
<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="../js/admin.min.js"></script>

<script src="../vendor/datatables/jquery.dataTables.min.js"></script>
<script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

<script src="../js/script.js"></script>

<!-- Gestion des graphes -->
<script src="../vendor/chart.js/Chart.min.js"></script>
<script src="../js/demo/chart-area-demo.js"></script>
<script src="../js/demo/chart-pie-demo.js"></script>

</body>

</html>
