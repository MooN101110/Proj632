
<link rel="stylesheet" type="text/css" href="page_accueil.css">

<div id="container">
<div id="menu">
    <h1>Learnagement</h1>
    <ul id="lemenu">
        <li><a href="?page=accueil&section=agenda" class="btn_menu ">Agenda</a></li>
        <li><a href="?page=accueil&section=etudiant" class="btn_menu ">Mon Compte</a></li>
        <li><a href="?page=accueil&section=rendus" class="btn_menu ">Mes Rendus</a></li>
        <li><a href="?page=accueil&section=liste_personnel" class="btn_menu ">Liste</a></li>
        <li><a href="?page=accueil&section=ressources" class="btn_menu ">Ressources</a></li>
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

