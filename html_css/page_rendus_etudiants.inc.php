<link rel="stylesheet" href="css/page_rendus.inc.css"/>

<?php

    echo "<h1> Etudiants </h1>";
    
//if($_SESSION["etudiant"]){
    $ajoute=false;
    /* Afficher la liste des rendus qu'un élèves a à faire*/
    echo "<h2>Liste des rendus qu'un élèves a à faire </h2> ";

    if (isset($_POST['checkbox']) && is_array($_POST['checkbox'])) {
        foreach ($_POST['checkbox'] as $selectedCheckbox) {
            $case=$selectedCheckbox;
            echo "La case ".$case;
            $sql="UPDATE INFO_rendus_eleves SET etat='ON' WHERE id_rendu =". $case . ";";
            $result=mysqli_query($conn, $sql) ; // on envoie la requête dans la base de donnée
            if($result){
                echo "Le travail a bien été rendu";
            }
        }   
    }

    /* Permettre a un élève de valider le dépôt d'un rendu */
    $sql="SELECT m.nom AS nom, date,description FROM `INFO_rendus_eleves` JOIN INFO_module m ON module LIKE m.code_module WHERE etat='OFF' ORDER BY date ASC";
    $result=mysqli_query($conn, $sql) or die ("Problème lors de la connexion");

    echo "<form method='post'> ";
    $i=0;
    while ($row=mysqli_fetch_array($result)) {
        echo "<input type='checkbox' name='checkbox[]' value=".$i." class='rendu'>
        <label for='choix".$i."'>".$row['nom']. " : ". $row['description']. ". Deadline : ".$row['date'] ."</label><br>";
        $i+=1;
        $module=$row['nom'];
    }
    echo "<button class='bouton' type='submit'>Valider les éléments finis</button>";
    echo "</form>";

    /* Afficher la liste des élèves qui ont rendus un rendus spécifiques*/
    
    ?>