<?php
    if (isset($_POST["mdp1"]) && isset($_POST["mdp2"])){
        if ($_POST["mdp1"] == $_POST["mdp2"]){
            $sql = "UPDATE INFO_utilisateur SET mot_de_passe = '".password_hash($_POST["mdp1"], PASSWORD_DEFAULT)."', mdp_change = '1' WHERE INFO_utilisateur.identifiant = '".$_POST['id']."'";
            $result = mysqli_query($conn, $sql);
            if (!$result){
                echo "<script>alert('Erreur liée à la base de données... :c')</script>";
                echo "<script>window.location.href='?page=connexion'</script>";
            }
            else{
                $_SESSION["connecte"]=true;
                $_SESSION["identifiant"]=$_POST["id"];
                //vérification si personne prof ou etu
                $sql1="SELECT id_etudiant FROM INFO_etudiant WHERE nom LIKE (SELECT nom FROM INFO_utilisateur WHERE identifiant LIKE '".$_POST["id"]."') and prenom LIKE (SELECT prenom FROM INFO_utilisateur WHERE identifiant LIKE '".$_POST["id"]."')";
                $result1 = mysqli_query($conn, $sql1) or die("Requête invalide: ". mysqli_error( $conn )."\n".$sql1);
                $val= mysqli_fetch_array($result1);
                if ($val["id_etudiant"]){
                    $_SESSION["type"]="etudiant";
                }
                else{
                    $_SESSION["type"]="enseignant";
                }
            
                echo "<script>alert('Changement de mot de passe réussi ! :)')</script>";
                echo "<script>window.location.href='?page=accueil'</script>";
            }
        }
    }
    else{
        echo "<script>alert('Les mots de passe ne sont pas identiques ! :c')</script>";
        echo "<script>window.location.href='?page=connexion'</script>";
    }
?>