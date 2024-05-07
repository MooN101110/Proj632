<head>
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

        /* Afficher la liste des rendus qu'un enseignant a rentré*/
        echo "<h2>Liste des rendus qu'un enseignant à rentré </h2> ";
        $sql="SELECT m.nom AS nom, date,description FROM `INFO_rendus_eleves` JOIN INFO_module m ON module LIKE m.code_module ORDER BY date ASC";
        $result=mysqli_query($conn, $sql) or die ("Problème lors de la connexion");

        echo "<div id='listerenduseleves'><ul> ";
        while ($row=mysqli_fetch_array($result)) {
            echo"<li>".$row['nom']. " : ". $row['description']. ". Deadline : ".$row['date'] ." ";
        }
        echo "</ul></div>";

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



        /* Permettre à un enseignants de rajouter un rendus*/
        //echo "<h1> Remplir les éléments </h1>";
        /* Ajout de la liaison entre actionneur et zone*/
        /**if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Vérifier si la clé "choix" existe dans $_POST
            if (isset($_POST["menuactionneur"]) && isset($_POST["menuzones"])) {
                // Récupération des valeurs sélectionnées dans les listes déroulantes
                $actionneur = $_POST["menuactionneur"];
                $zone = $_POST["menuzones"];

                $sql="INSERT INTO actionneur2zone(id_actionneur,id_zone) VALUES ((SELECT id_actionneur FROM actionneur WHERE nom='".$actionneur."'),(SELECT id_zone FROM zone WHERE nom='".$zone ." '))\n";
                $result=mysqli_query($conn, $sql); // on envoie la requête dans la base de donnée
                if($result){
                    $ajoute=true;
                }
            }
        }
        /* Afficher la liste des élèves qui ont rendus un rendus spécifiques*/

        /* Permettre à un élève de valider le rendu d'un module*/
?>