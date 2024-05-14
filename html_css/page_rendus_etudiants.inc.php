

<?php
$ajout_enseignant=false;
echo "<h1> Enseignants </h1>";
    /* Afficher la liste des rendus qu'un enseignant a rentré */
    echo "<h2>Liste des rendus qu'un enseignant à rentré </h2> ";
    $sql="SELECT m.nom AS nom, date,description FROM `INFO_rendus_eleves` JOIN INFO_module m ON module LIKE m.code_module ORDER BY date ASC";
    $result=mysqli_query($conn, $sql) or die ("Problème lors de la connexion");

    echo "<div id='listerenduseleves'><ul> ";
    while ($row=mysqli_fetch_array($result)) {
        echo"<li>".$row['nom']. " : ". $row['description']. ". Deadline : ".$row['date'] ." ";
    }
    echo "</ul></div>";
        /* Permettre à un enseignants de rajouter un rendus*/
        echo "<h2>Rentrer un nouveau rendu </h2>";

        echo "<form action='https://tp-epua.univ-savoie.fr/~rechonre/Proj632/html_css/page_rendus.php' method='post'>";
        echo "<label for='menumodule'>Sélectionner un module</label><br>";
        echo "<select name='menumodule'>";
        //On ne sélectionne que les actionneurs qui ne sont pas affectés au moins une fois à une zone géographique
        $sql="SELECT nom FROM INFO_module";
        $result=mysqli_query($conn, $sql) or die("Problème lors de la connexion"); // on envoie la requête dans la base de donnée
        while ($row=mysqli_fetch_array($result)) {
            echo "<option value'". $row["nom"]."'>".$row["nom"]. "</option>";
        }  
        echo "</select>";
    
        echo "<br>Description : <input type='text' name='descriprition_enseignant' value='OFF'></br>";
        echo "<input type='date' name='date_saisie' value='OFF'>";
        echo "<button type='submit'>Valider</button>";
        echo "</form>";

        
        if (isset($_POST['description_enseignant'])) {
            echo 'passe par là';
            // On récupère les données du formulaire pour les ajouter à la base de donnée
            $nom=$_POST["nom"];
            $desciption=$_POST["descriprition_enseignant"];
            $date_saisie=$_POST["date_saisie"];

            //On sélectionne l'id rendu le plus important et on lui rajoutera 1 :
            $sql="SELECT MAX(id_rendu) FROM `INFO_rendus`";
            $result=mysqli_query($conn, $sql);
            while ($row=mysqli_fetch_array($result)) {
                echo "id_rendu max : ". $row[0];
            }

            $sql="INSERT INTO INFO_rendus(module, date, description) VALUES ('".$nom."', '".$description."', '".$date_saisie."');";
            $result=mysqli_query($conn, $sql); // on envoie la requête dans la base de donnée
            if($result){
                $ajout_enseignant=true;
            }
        }

        /* Afficher un message si l'élément a bien été rajouté */
        if ($ajout_enseignant) {
            // On vérifie qu'il y a bien un élément ajouté
            if (mysqli_affected_rows($conn) > 0) {
                // L'élément a été ajouté avec succès à la liste
                echo "L'élément a été ajouté avec succès à la liste.";
            } else {
                // Aucune ligne n'a été insérée dans la base de données
                echo "Erreur : Aucune ligne n'a été insérée dans la base de données.";
            }
        } else {
            // Erreur lors de l'ajout de l'élément
            //On stocke dans $errorCode le type d'erreur qui a été soulevé
            $errorCode = mysqli_errno($conn);
            // On affiche l'erreur en question, du moins le message qu'elle retourne
            echo "Erreur lors de l'ajout de l'élément : " . mysqli_error($conn);

        }

        /*Afficher la liste des rendus pour une promo => seulement dans le mode enseignant */
?>