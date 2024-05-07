<?php
    if (isset($_POST["mdp1"]) && isset($_POST["mdp2"])){
        if ($_POST["mdp1"] == $_POST["mdp2"]){
            $sql = "UPDATE INFO_utilisateur SET mot_de_passe = '".$_POST["mdp1"]."', mdp_change = '1' WHERE INFO_utilisateur.identifiant = '".$_POST['id']."'";
            $result = mysqli_query($conn, $sql);
            if (!$result){
                
                echo "<script>alert('Erreur liée à la base de données... :c')</script>";
                echo "<script>window.location.href='?page=connexion'</script>";
            }
            else{
                echo "<script>alert('Changement de mot de passe réussi ! :)')</script>";
            }
        }
        else{
            echo "<script>alert('Les mots de passe ne sont pas identiques ! :c')</script>";
            echo "<script>window.location.href='?page=connexion'</script>";
        }
    }
?>

<p>Bienvenu</p>

<a href="page_ressources.php" class="btn_ressources" >Ressources</a>