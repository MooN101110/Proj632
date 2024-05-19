<?php
if (!isset($_SESSION)){
    session_start([
        'cookie_lifetime' => 86400,
    ]);

    if (!isset($_SESSION["connecte"])){
        $_SESSION["connecte"] = false;
        $_SESSION["identifiant"] = "";
        $_SESSION["type"]=""; //etudiant ou enseignant
    }
}
?>

<!DOCTYPE html>

<html>
<head>
    <title>Learnagement</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="css/learnagment.css">
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
    <div id="contenu">
            <?php
            if(!isset($_GET["page"]) ) { 
                $page="connexion";
            } else {
                $page=$_GET["page"];
            }

            if (file_exists("page_".$page.".inc.php")){
                include("page_".$page.".inc.php");
            }
            else{
                echo "Page non trouvée";
            }
    ?>
        
    </div>
    <div id="piedpage">

        <footer> 
        <?php
        if($_SESSION["connecte"]){
            echo "Connecté en tant que : ".$_SESSION["identifiant"]." - ";
        }
        else {
            echo "Non connecté - ";
        }
        ?>
        Polytech Annecy-Chambéry - APP - Learnagement</footer>
        </div>
</body>
