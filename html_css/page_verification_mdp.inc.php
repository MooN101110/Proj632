<?php
if( isset( $_POST["inscription_ok"])){
  $sql="SELECT mot_de_passe, mdp_change FROM INFO_utilisateur WHERE identifiant LIKE '".$_POST["id"]."'";
  $result = mysqli_query($conn, $sql) or die("Requête invalide: ". mysqli_error( $conn )."\n".$sql);
  $val = mysqli_fetch_array($result);

  if ($val['mdp_change'] == 1 && password_verify($_POST['mdp'], $val['mot_de_passe'])){
      echo "<script>window.location.href='?page=accueil'</script>";
  }
  else if ($val['mdp_change'] == 0 && $val['mot_de_passe'] == $_POST['mdp']){
      $id = $_POST['id'];
      echo "<div class='containter'><h1>Initialisation du mot de passe</h1>";
      echo "<form action='?page=accueil' method='post'>";
      echo "<p>(Utilisateur : ".$id.")</p>";
      echo "<input type='password' name='mdp1' placeholder='Mot de passe' required><br>";
      echo "<input type='password' name='mdp2' placeholder='Confirmer mot de passe' required><br>";
      echo "<input type='hidden' name='id' value='".$id."'>";
      echo "<input type='submit' value='Valider'>";
      echo "</div>";
  }
  else{
    echo "<script>alert('Erreur - Identifiants incorrects !')</script>";
    echo "<script>window.location.href='?page=connexion'</script>";
  }
}
?>