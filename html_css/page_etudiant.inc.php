<link rel="stylesheet" href="css/page_etudiant.inc.css"/>

<?php
// id_login est récupéré dans l'url 
$id_login=$_SESSION["identifiant"];

/* Lancement du fichier de scraping des informations de l'étudiant */
/*$pythonScript='../python/scraping_polypoint_stage.py';
$output=shell_exec('python ' . $pythonScript);
 */

$sql="SELECT nom, prenom FROM INFO_utilisateur u WHERE u.identifiant='".$id_login."'"; //id_login : correspond au login entrée par la personne dans la page de connexion
$result=mysqli_query($conn, $sql);
$row=mysqli_fetch_array($result);

echo "<h2> Page étudiante - ".$row['nom']." ".$row['prenom']." </h2>";

//Menu pour les étudiants
echo "<ul id='menu'>";
echo "</ul>";

//Partie sur les polypoints
echo "<h3> Polypoints : </h3>";
// affichage par année
$sql_annee="SELECT DISTINCT annee AS annee FROM INFO_polypoint WHERE id_etudiant='".$id_login."'";
$result=mysqli_query($conn, $sql_annee);
while($row = mysqli_fetch_array($result)){
    $annee=$row["annee"];
    $sql_somme="SELECT SUM(nb_points) AS somme_polypoint, annee AS annee FROM INFO_polypoint WHERE annee='".$annee."' AND id_etudiant='".$id_login."'";
    $result_somme=mysqli_query($conn, $sql_somme); // on obtient un int
    $row_somme=mysqli_fetch_array($result_somme);
    $somme=strval($row_somme["somme_polypoint"]);
    if($somme>1){
        echo "<p id=polypoints_par_annee> Année ".$annee." : ".$somme." polypoints enregistrés </p>";
    }
    if($somme==1){
        echo "<p id=polypoint_par_annee> Année ".$annee." : ".$somme." polypoint enregistré </p>";
    }
}


// affichage de la liste de polypoints
$sql="SELECT intitule, tache, nb_points, annee FROM INFO_polypoint p WHERE p.id_etudiant='".$id_login."'"; //id_login : correspond au login entrée par la personne dans la page de connexion
$result=mysqli_query($conn, $sql);

//si on a des données associées à des polypoints :
if ($row = mysqli_fetch_array($result)){
    echo "<table id='polypoints'>";
    echo "<tr id='entete_polypoints'>
            <th>Action</th>
            <th>Détail</th>
            <th>Nombre</th>
            <th>Année concernée</th>
        </tr>";
    // insertions des données de polypoint dans un tableau
    do {
        echo "<tr>";
        echo "<td id='intitule_polypoints'>".$row['intitule']."</td>";
        echo "<td id='tache_polypoints'>".$row['tache']."</td>";
        echo "<td id='nb_polypoints'>".$row['nb_points']."</td>";
        echo "<td id='annee_polypoints'>".$row['annee']."</td>";
        echo "</tr>";
    }
    while ($row = mysqli_fetch_array($result)); // tant qu'on a une ligne de résultat
    echo "</table>";
}
// si on a pas de polypoints :
else{
    echo  "<p id='pas_donnee'>Aucun polypoint enregistré. \nN'oubliez pas d'effectuer différentes actions dans l'année scolaire.</p>";
}




//Partie sur les stages
echo "<h3> Stages : </h3>";

$sql="SELECT annee, entreprise, date  FROM INFO_stage s WHERE s.id_etudiant='".$id_login."'"; //id_login : correspond au login entrée par la personne dans la page de connexion
$result=mysqli_query($conn, $sql);
// si on a déjà des stages d'enregistrés : 
if ($row = mysqli_fetch_array($result)){
    echo "<table id='stages'>";
    echo "<tr id='entete_stages'>
            <th>Année </th>
            <th>Entreprise</th>
            <th>Dates</th>
        </tr>";
        // insertions des données de polypoint dans un tableau
        do{
            echo "<tr>";
            echo "<td id='annee_stage'>".$row['annee']."</td>";
            echo "<td id='entreprise_stage'>".$row['entreprise']."</td>";
            echo "<td id='date_stage'>".$row['date']."</td>";
            echo "</tr>";
        }
        while ($row = mysqli_fetch_array($result)) ;// tant qu'on a une ligne de résultat
        echo "</table>";
}
// si on a pas de stages enregistrés :
else{
    echo  "<p id='pas_donnee'>Aucun stage n'est connu du service. \nN'oubliez pas de faire vos demandes sur l'intranet.</p>";
}





?>