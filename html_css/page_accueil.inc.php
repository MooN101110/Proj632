<!DOCTYPE html>

<html>
<head>
    <title>Learnagement</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="page_accueil.css">
</head>

<?php 
    $logs = file("../logs.txt");
    $conn = @mysqli_connect("tp-epua:3308", substr($logs[0],0,-2), substr($logs[1],0,-2));
    if (mysqli_connect_errno()){
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    } else {
        mysqli_select_db($conn, substr($logs[0],0,-2));
        mysqli_query($conn, "SET NAMES UTF8");
    }
?>

<body>
    <div id="container">
    <div id="menu">
        <h1>Learnagement</h1>
        <ul id="lemenu">
            <li><a href="?page=accueil&id=camasl&section=agenda" class="btn_menu ">Agenda</a></li>
            <li><a href="?page=accueil&id=camasl&section=etudiant" class="btn_menu ">Mon Compte</a></li>
            <li><a href="?page=accueil&id=camasl&section=rendus" class="btn_menu ">Mes Rendus</a></li>
            <li><a href="?page=accueil&id=camasl&section=ressources" class="btn_menu ">Ressources</a></li>
        </ul>
    </div>
    <div id="contenu_section">

    <?php
    if(!isset($_GET["section"]) ) { 
        $section="agenda";
    } else {
        $section=$_GET["section"];
    }

    if (file_exists("page_".$section.".inc.php")){
        include("page_".$section.".inc.php");
    }
    else{
        echo "Page non trouvée";
    }
    ?>
    </div>
</div>
</body>
