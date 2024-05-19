<link rel="stylesheet" href="css/page_rendus.inc.css"/>


<?php
//if($_SESSION["enseignant"]){
$ajout=false;
echo "<h1> Enseignants </h1>";
    /* Afficher la liste des rendus qu'un enseignant a rentré */
    echo "<h2>Liste des rendus que les élèves ont </h2> ";
    $sql="SELECT m.nom AS nom, date,description FROM `INFO_rendus_eleves` JOIN INFO_module m ON module LIKE m.code_module ORDER BY date ASC";
    $result=mysqli_query($conn, $sql) or die ("Problème lors de la connexion");

    echo "<div id='listerenduseleves'><ul> ";
    while ($row=mysqli_fetch_array($result)) {
        echo"<li>".$row['nom']. " : ". $row['description']. ". Deadline : ".$row['date'] ." ";
    }
    echo "</ul></div>";
        /* Permettre à un enseignants de rajouter un rendus*/
        echo "<h2>Rentrer un nouveau rendu </h2>";

        echo "<form action='?page=rendus_enseignants' method='post'>";
        echo "<label>Sélectionner un module</label><br>";
        echo "<select name='nom'>";
        //On ne sélectionne que les actionneurs qui ne sont pas affectés au moins une fois à une zone géographique
        $sql="SELECT nom FROM INFO_module";
        $result=mysqli_query($conn, $sql) or die("Problème lors de la connexion"); // on envoie la requête dans la base de donnée
        while ($row=mysqli_fetch_array($result)) {
            echo "<option value'". $row["nom"]."'>".$row["nom"]. "</option>";
        }  

        echo "</select>";
        echo "<br>Description : <input type='text' name='description' value='OFF'></br>";
        echo "<input type='date' name='date_saisie' value='OFF'>";
        echo "<button class='bouton' type='submit'>Valider</button>";
        echo "</form>";

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // On récupère les données du formulaire pour les ajouter à la base de donnée
            $nom=$_POST["nom"];
            $description=$_POST["description"];
            $date_saisie=$_POST["date_saisie"];
            echo $nom." ; ".$description." ; ".$date_saisie;
        
            $sql="SELECT MAX(id_rendu) as max FROM `INFO_rendus`";
            $result=mysqli_query($conn, $sql);
            while ($row=mysqli_fetch_array($result)) {
                $max=$row["max"]+1;
            }
            // Vérification que la description n'est pas 'OFF'
            echo $description."<br>";
            if ($description !== 'OFF') {
                $sql="INSERT INTO INFO_rendus(id_rendu, module, date, description) VALUES ('".$max."','".$nom."', '".$date_saisie."', '".$description."');";
                $result=mysqli_query($conn, $sql); // on envoie la requête dans la base de donnée
                if($result){
                    $ajout=true;
                }
            } else {
                echo "La description ne peut pas être 'OFF'. Veuillez entrer une description valide.";
            }
        }

        /* Afficher un message si l'élément a bien été rajouté */
        if ($ajout) {
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