<link rel="stylesheet" href="css/page_info_enseignant.inc.css"/>

<?php
/*
    Format table 'INFO_enseignant':
    id_enseignant INT,
    prenom VARCHAR(25),
    nom VARCHAR(25),
    mail VHARCHAR(50)
*/

if(isset($_GET["id"])){
    if (isset($_GET["id"])){
        $search = $_GET["id"];
        $sql = "SELECT * FROM INFO_enseignant WHERE id_enseignant = '".$search."'";
        $result = mysqli_query($conn, $sql);
    }
    else {
        echo "<div id='error-message'>";
        echo "<p>Erreur :(<br>Informations probablement erronées...</p>";
        echo "</div>";
        return;
    }
    if (mysqli_num_rows($result) == 0) {
        echo "<div id='error-message'>";
        echo "<p>Erreur :(<br>Informations probablement erronées...</p>";
        echo "</div>";
        return;
    }
    echo "<a href='?page=accueil&section=liste_prof' class='bouton_retour'>Retour</a>";
    echo "<div id=infos_enseignant>";
    $row = mysqli_fetch_assoc($result);
    echo "<h2>&#128100;".$row["prenom"]." ".$row["nom"]."</h2>";
    echo "<p>Mail : <a href='mailto:".$row['mail']."'>".$row['mail']."</a> </p>";
    echo "</div>";
    
}
else{
    $id=$_SESSION["identifiant"];

        $sql = "SELECT * FROM INFO_enseignant WHERE nom LIKE (SELECT nom FROM INFO_utilisateur WHERE identifiant LIKE '".$id."') and prenom LIKE (SELECT prenom FROM INFO_utilisateur WHERE identifiant LIKE '".$id."')";
        $result = mysqli_query($conn, $sql);
        $row= mysqli_fetch_array($result);

    if (!$row["id_enseignant"]){ 
        echo "<div id='error-message'>";
        echo "<p>Erreur :(<br>Informations probablement erronées...</p>";
        echo "</div>";
        return;
    }
    echo "<div id=infos_enseignant>";
    echo "<h2>&#128100;".$row["prenom"]." ".$row["nom"]."</h2>";
    echo "<p>Mail : <a href='mailto:".$row['mail']."'>".$row['mail']."</a> </p>";
    echo "</div>";
}
?>
