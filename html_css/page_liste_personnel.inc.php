<?php
echo "<form action='https://tp-epua.univ-savoie.fr/~royax/proj632/html_css/learnagement.php?page=liste_personnel.php method='get'>";
    echo "<label id='formulaire' for='choix'>Selectionnez la liste à afficher : </label>";
    echo "<select name='liste' id='form'>";
    echo "<option value=liste_prof> Liste des enseignants </option>"; 
    echo "<option value=liste_elev> Liste des étudiants </option>"; 
    echo "</select>";
    echo"</br>";
    echo "<input type='hidden' name='validation' value='true'>"; // Ajout de la variable cachée pour indiquer que le formulaire a été soumis
    echo "<button id=bouton type='submit' name='validation' >Valider </button><br/>";
    echo "</form>";





?>