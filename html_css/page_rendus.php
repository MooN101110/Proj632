<head>
    <title></title>
    <meta content="info">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="" />  
  
  <?php   
        $id_login="rechonre";
      /*Connexion à la base de données sur le serveur tp-epua*/
		$conn = @mysqli_connect("tp-epua:3308", "rechonre", "aHuMsl6q");    
		
		/*connexion à la base de donnée depuis la machine virtuelle INFO642*/
		/*$conn = @mysqli_connect("localhost", "etu", "bdtw2021");*/  

		if (mysqli_connect_errno()) {
            $msg = "erreur ". mysqli_connect_error();
        } else {  
            $msg = "connecté au serveur " . mysqli_get_host_info($conn);
            /*Sélection de la base de données*/
            mysqli_select_db($conn, "rechonre"); 
            /*mysqli_select_db($conn, "etu"); */ /*sélection de la base sous la VM info642*/
		
            /*Encodage UTF8 pour les échanges avecla BD*/
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

        /* Afficher la liste des rendus qu'un élèves a à faire*/
        echo "<h2>Liste des rendus qu'un élèves a à faire </h2> ";
        $sql="SELECT m.nom AS nom, date,description FROM `INFO_rendus` JOIN INFO_module m ON module LIKE m.code_module ORDER BY date ASC";
        $result=mysqli_query($conn, $sql) or die ("Problème lors de la connexion");

        echo "<form> ";
        while ($row=mysqli_fetch_array($result)) {
            echo "<input type='checkbox' id='choix' name='choix1' value='choix1'>
            <label for='choix1'>".$row['nom']. " : ". $row['description']. ". Deadline : ".$row['date'] ."</label><br>";
        }
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