
<form method='post' >
  <input type='text' name='nom' placeholder="Nom" required><br/>
  <input type='text' name='prenom' placeholder="Prenom" required><br/>
  <input type="radio" id="etudiant" name="personne" value="Etudiant">
  <label for="etudiant">Etudiant</label>
  <input type="radio" id="enseignant" name="personne" value="Enseignant">
  <label for="enseignant">Enseignant</label><br>
  <input type='text' name="mdp" placeholder="Mot de passe" required></br>
  <button name='inscription_ok' type='submit'>S'inscrire</button>
</form>

<?php
if( isset( $_POST["inscription_ok"])){
        /*Connexion à la bd*/
        $conn = @mysqli_connect("gpu-epu.univ-savoie.fr:48080", "root", "RootAuPif");
        mysqli_select_db($conn, "learnagement"); 
        mysqli_query($conn, "SET NAMES UTF8");
        echo"azerty";
}
?>