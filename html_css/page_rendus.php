<head>
    <title></title>
    <meta content="info">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="" />   //ajouter le lien vers la page CSS
</head>
  
  <?php   
        /*Connexion à la base de données sur le serveur gpu-epua*/
        $ressource = fopen('logs_db.txt', 'rb');
        $user=fgets($ressource, 5);
        $mdp=fgets($ressource,11);
        //revoir la connexion 
		$conn = @mysqli_connect("gpu-epua", $user,$mdp);    
		
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
?>