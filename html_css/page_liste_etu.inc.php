<?php
// Affichage des élèves
echo "<p> Liste des élèves : </p>";
//Affichage de la barre de tri
echo "<form method='post' action='?page=liste_etu'>";
echo "<label id='formulaire' for='choix'>Sélectionnez la filière à afficher : </label>";
// selection de la filiere
echo "<select name='filiere' id='form'>";
    $sql="SELECT DISTINCT filiere from INFO_etudiant";
    $res=mysqli_query($conn, $sql);
    echo "<option value=pas_filiere>    </option>";
    while ($row = mysqli_fetch_array($res)) { // tant qu'on a une ligne de résultat
        echo "<option value=".$row['filiere'].">".$row['filiere']."</option>"; // on affiche l'option correspondante
    }
echo "</select>";
// selection de l'année
echo "<select name='annee' id='form'>";
    $sql="SELECT DISTINCT annee from INFO_etudiant";
    $res=mysqli_query($conn, $sql);
    echo "<option value=pas_annee>    </option>";
    while ($row = mysqli_fetch_array($res)) { // tant qu'on a une ligne de résultat
        echo "<option value=".$row['annee'].">".$row['annee']."</option>"; // on affiche l'option correspondante
    }
echo "</select>";
echo "<input type='hidden' name='valider' value='true'>"; // Ajout de la variable cachée pour indiquer que le formulaire a été soumis
echo "<button id=bouton type='valider'>Valider</button>";
echo "<a href='?page=liste_personnel' class='bouton_retour'>Retour</a>";
echo "</form>";

// Affichage de la liste
if (isset($_POST['valider'])) {
    $filiere=$_POST['filiere'];
    $annee=$_POST['annee'];
    /*
    // si on a pas de filiere :
    if ($filiere="pas_filiere" && $annee!="pas_annee"){
        //affichage des élèves en fonction de l'année selectionnée
        echo "Elèves en ".$annee." : ";
    }

    // si on a pas d'année :
    elseif ($annee="pas_annee" && $filiere!="pas_filiere"){
        //affichage des élèves en fonction de la filière selectionnée
        print($filiere);
        echo "Elèves de ".$filiere." : ";
    }*/

    /*else*/ if($annee!="pas_annee" && $filiere!="pas_filiere"){
        $sql="SELECT prenom as etu_prenom, nom as etu_nom FROM INFO_etudiant WHERE filiere='{$filiere}' AND annee='{$annee}'";
        $result=mysqli_query($conn, $sql);
        if (mysqli_num_rows($result)>0) {
            echo "<p>Elèves de ".$filiere." en ".$annee." : </p>";
            while ($row=mysqli_fetch_array($result)){ 
                echo "<li>".$row['etu_nom']." ".$row['etu_prenom']."</li>"; 
                }
            }
        else{
            echo "<p>Il n'y a pas d'étudiants correspondant à la recherche : ".$filiere." en ".$annee.".</p>";
        }
    }
        
    

}
// si on a pas de confidion on affiche toute la liste
else {
    $sql="SELECT prenom as etu_prenom, nom as etu_nom, annee as etu_annee, filiere as etu_filiere FROM INFO_etudiant";
    $result=mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_array($result)){ 
        echo "<li>".$row['etu_nom']." ".$row['etu_prenom']." " .$row['etu_annee']." " .$row['etu_filiere']."</li>"; 
    }
} 

?>