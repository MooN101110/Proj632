<link rel="stylesheet" href="css/page_liste_personnel.inc.css"/>

<?php

//Formulaire pour choisir la liste à afficher
echo "<form method='post' action='?page=accueil&section=liste_personnel'>";
    echo "<label id='formulaire' for='choix'>Selectionnez la liste à afficher : </label>";
    echo "<select name='liste' id='form'>";
    echo "<option value=liste_prof> Enseignants </option>"; 
    echo "<option value=liste_etu> Etudiants </option>"; 
    echo "</select>";
    echo "<input type='hidden' name='submit' value='true'>"; // Ajout de la variable cachée pour indiquer que le formulaire a été soumis
    echo "<button class='bouton_retour' type='submit'>Valider</button><br/>";
echo "</form>";


//Affichage de la liste selectionnée 
if (isset($_POST['submit'])) {
    $liste=$_POST['liste'];
    include("page_".$liste.".inc.php");
}

?>