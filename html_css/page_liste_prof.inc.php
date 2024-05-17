<link rel="stylesheet" href="css/page_liste_personnel.inc.css"/>

<?php
// Affichage des professeurs
echo "<p> Liste des professeurs : </p>";
//Affichage de la barre de recherche
echo "<form method='post' action='?page=accueil&section=liste_prof'>";
echo "<input type='text' name='query' placeholder='Entrez une recherche'>";
echo "<input class='bouton_retour' type='submit' name=bouton_recherche value='Rechercher'>";
echo "<a href='?page=accueil&section=liste_personnel' class='bouton_retour'>Retour</a>";
echo "</form>";


// Affichage de la liste
// si y'a une recherche spéficique 
if (isset($_POST['bouton_recherche'])){
    $query=$_POST["query"];
    $sql="SELECT id_enseignant as prof_id, prenom as prof_prenom, nom as prof_nom, mail as prof_mail FROM INFO_enseignant WHERE mail LIKE '%$query%'";
    $result=mysqli_query($conn, $sql);
    if (mysqli_num_rows($result)>0) {
        echo "<div id='enseignants'>";
        while ($row = mysqli_fetch_array($result)){ 
            echo "<div id='enseignant'> <a id='info_prof' href='?page=accueil&section=info_enseignant&id=".$row['prof_id']."'>".$row['prof_nom']." ".$row['prof_prenom']."</a> <a id='mail_prof' href='mailto:".$row['prof_mail']."'>".$row['prof_mail']."</a></div>";
            }
        echo "</div>";
    }
    else{
        echo "<p id='erreur_recherche'>Il n'y a pas de professeurs correspondant à la recherche : ".$query.".</p>";
    }
}

// si y'a pas de recherche spéficique
else{
    $sql="SELECT id_enseignant as prof_id, prenom as prof_prenom, nom as prof_nom, mail as prof_mail FROM INFO_enseignant";
    $result=mysqli_query($conn, $sql);
    echo "<div id='enseignants'>";
    while ($row = mysqli_fetch_array($result)){ 
        echo "<div id='enseignant'> <a id='info_prof' href='?page=accueil&section=info_enseignant&id=".$row['prof_id']."'>".$row['prof_nom']." ".$row['prof_prenom']."</a> <a id='mail_prof' href='mailto:".$row['prof_mail']."'>".$row['prof_mail']."</a></div>";
        }
    echo "</div>";
    }

?>