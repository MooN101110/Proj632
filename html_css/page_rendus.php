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

        /* Afficher la liste des rendus pour un élève*/
        echo "<h2>Liste des rendus qu'un élève a à rendre </h2> ";
        $sql="SELECT m.nom AS nom, date,description FROM `INFO_rendus_eleves` JOIN INFO_module m ON module LIKE m.code_module ORDER BY date ASC";
        $result=mysqli_query($conn, $sql) or die ("Problème lors de la connexion");

        echo "<div id='listerenduseleves'><ul> ";
        while ($row=mysqli_fetch_array($result)) {
            echo"<li>".$row['nom']. " : ". $row['description']. ". Deadline : ".$row['date'] ." ";
        }
        echo "</ul></div>";

        /* Afficher la liste des rendus qu'un enseignants a mis*/
        echo "<h2>Liste des rendus qu'un enseignants à afficher </h2> ";
        $sql="SELECT m.nom AS nom, date,description FROM `INFO_rendus_eleves` JOIN INFO_module m ON module LIKE m.code_module ORDER BY date ASC";
        $result=mysqli_query($conn, $sql) or die ("Problème lors de la connexion");

        echo "<div id='listerenduseleves'><ul> ";
        while ($row=mysqli_fetch_array($result)) {
            echo"<li>".$row['nom']. " : ". $row['description']. ". Deadline : ".$row['date'] ." ";
        }
        echo "</ul></div>";

        /* Permettre à un enseignants de rajouter un rendus*/

        /* Afficher la liste des élèves qui ont rendus un rendus spécifiques*/

        /* Permettre à un élève de valider le rendu d'un module*/
