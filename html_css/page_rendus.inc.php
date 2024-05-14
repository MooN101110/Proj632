<head>
    <!--Lien vers le site en construction : https://tp-epua.univ-savoie.fr/~rechonre/Proj632/html_css/page_rendus.php?choix0=choix -->
    <title></title>
    <meta content="info">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="" />  
  
<?php 
    $logs = file("../logs.txt");
    $conn = @mysqli_connect("tp-epua:3308", substr($logs[0],0,-2), substr($logs[1],0,-2));
    if (mysqli_connect_errno()){
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    } else {
        mysqli_select_db($conn, substr($logs[0],0,-2));
        mysqli_query($conn, "SET NAMES UTF8");
    }
    //Déclaration des varibales vérifiant les ajouts
    $ajoute=false;
    $ajout_enseignant=false;

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

    echo "<h1> Etudiants </h1>";
    $sql="SELECT m.nom AS nom, date,description FROM `INFO_rendus_eleves` JOIN INFO_module m ON module LIKE m.code_module WHERE etat='OFF' ORDER BY date ASC";
    $result=mysqli_query($conn, $sql) or die ("Problème lors de la connexion");

    echo "<form method='post'> ";
    $i=0;
    while ($row=mysqli_fetch_array($result)) {
        echo "<input type='checkbox' name='checkbox[]' value=".$i." class='non-rendus'>
        <label for='choix".$i."'>".$row['nom']. " : ". $row['description']. ". Deadline : ".$row['date'] ."</label><br>";
        $i+=1;
        $module=$row['nom'];
    }
    $sql="SELECT m.nom AS nom, date,description FROM `INFO_rendus_eleves` JOIN INFO_module m ON module LIKE m.code_module WHERE etat='ON' ORDER BY date ASC";
    $result=mysqli_query($conn, $sql) or die ("Problème lors de la connexion");
    /* Permettre a un élève de valider le dépôt d'un rendu */
    echo "<form method='post'> ";
    $i=0;
    while ($row=mysqli_fetch_array($result)) {
        echo "<input type='checkbox' name='checkbox[]' value=".$i." class='rendu'>
        <label for='choix".$i."'>".$row['nom']. " : ". $row['description']. ". Deadline : ".$row['date'] ."</label><br>";
        $i+=1;
        $module=$row['nom'];
    }
    echo "<button type='submit'>Valider les éléments finis</button>";
    echo "</form>";


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
?>