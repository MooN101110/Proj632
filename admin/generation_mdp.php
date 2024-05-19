<!DOCTYPE html>

<html>
<head>
    <title>Admin - Learnagement</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <?php

        set_time_limit(300);

        function motDePasse($longueur=8) { // 8 = longueur par défaut
            // chaine de caractères qui sera mis dans le désordre:
            $Chaine = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ&$%-_!?";
            // on mélange la chaine avec la fonction str_shuffle(), propre à PHP
            $Chaine = str_shuffle($Chaine);
            // ensuite on coupe à la longueur voulue avec la fonction substr(), propre à PHP aussi
            $Chaine = substr($Chaine,0,$longueur);
            // ensuite on retourne notre chaine aléatoire de "longueur" caractères:
            return $Chaine;
            }

        function identifiant($nom,$prenom){
            $nom = strtolower($nom);
            $prenom = strtolower($prenom);
            $nom = str_replace(" ","",$nom);
            $nom = str_replace("-","", $nom);
            $prenom = str_replace(" ","",$prenom);
            $prenom = str_replace("-","",$prenom);

            if (strlen($nom)>4){
                $id = substr($nom,0,7).substr($prenom,0,1);
            }
            else{
                $id = $nom.substr($prenom,0,5-strlen($nom));
            }
            $conn = @mysqli_connect("tp-epua:3308", $_POST["id"],$_POST["mdp"]);
            mysqli_select_db($conn,$_POST["id"]);
            mysqli_query($conn, "SET NAMES UTF8");

            $sql = "SELECT * FROM INFO_utilisateur WHERE identifiant LIKE '".$id."' LIMIT 1";
            $resultat = mysqli_query($conn, $sql) or die("Requête invalide: ". mysqli_error( $conn )."\n".$sql);
            if (mysqli_num_rows($resultat) == 0){
                return $id;
            }
            else{
                $id = $id.substr($prenom,5-strlen($prenom),5-strlen($prenom)+1);
                return $id;
            }
        }


        if (isset($_POST["id"])&&isset($_POST["mdp"])){
            $conn = @mysqli_connect("tp-epua:3308", $_POST["id"],$_POST["mdp"]);
            if (mysqli_connect_errno()){
                echo "Failed to connect to MySQL: " . mysqli_connect_error();
            } 
        else {
            mysqli_select_db($conn,$_POST["id"]);
            mysqli_query($conn, "SET NAMES UTF8");
            $sql = "DELETE FROM INFO_utilisateur";
            $resultat = mysqli_query($conn, $sql) or die("Requête invalide: ". mysqli_error( $conn )."\n".$sql);
            
            $sql = "SELECT nom,prenom from INFO_etudiant UNION SELECT nom,prenom from INFO_enseignant";
            $resultat = mysqli_query($conn, $sql) or die("Requête invalide: ". mysqli_error( $conn )."\n".$sql);

            while(($ligne = mysqli_fetch_array($resultat))){
                $sql = "INSERT INTO INFO_utilisateur(nom,prenom,identifiant,mot_de_passe,mdp_change) VALUES ('".$ligne['nom']."','".$ligne['prenom']."','".identifiant($ligne['nom'],$ligne['prenom'])."','".password_hash(motDePasse(), PASSWORD_DEFAULT)."',0)";
                $resultat1 = mysqli_query($conn, $sql) or die("Requête invalide: ". mysqli_error( $conn )."\n".$sql);

            }

        }
            
        }
        else {
            echo "<h1>Réinitialisation des mots de passe :</h1>";
            echo "<form method='post'><br>";
            echo "<input type='text' name='id' placeholder='Identifiant'><br>";
            echo "<input type='password' name='mdp'placeholder='Mot de passe'><br>";
            echo "<input type='submit' value='Valider'></input>";
        }
    ?>
</body>
</html>