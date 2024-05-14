<?php
// Affichage des professeurs
echo "<p> Liste des professeurs : </p>";
//Affichage de la barre de recherche
echo "<form method='post' action='?page=liste_prof'>";
echo "<input type='text' name='query' placeholder='Entrez une recherche'>";
echo "<input type='submit' name=bouton_recherche value='Rechercher'>";
echo "<a href='?page=liste_personnel' class='bouton_retour'>Retour</a>";
echo "</form>";


// Affichage de la liste
// si y'a une recherche spéficique 
if (isset($_POST['bouton_recherche'])){
    $query=$_POST["query"];
    $sql="SELECT prenom as prof_prenom, nom as prof_nom, mail as prof_mail FROM INFO_enseignant WHERE mail LIKE '%$query%'";
    $result=mysqli_query($conn, $sql);
    if (mysqli_num_rows($result)>0) {
        while ($row = mysqli_fetch_array($result)){ 
            echo "<li>".$row['prof_nom']." ".$row['prof_prenom']." " .$row['prof_mail']."</li>";   
        }
    }
    else{
        echo "<p>Il n'y a pas de professeurs correspondant à la recherche : ".$query.".</p>";
    }
}

// si y'a pas de recherche spéficique
else{
    $sql="SELECT prenom as prof_prenom, nom as prof_nom, mail as prof_mail FROM INFO_enseignant";
    $result=mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_array($result)){ 
        echo "<li>".$row['prof_nom']." ".$row['prof_prenom']." " .$row['prof_mail']."</li>";   
        }
    }

?>