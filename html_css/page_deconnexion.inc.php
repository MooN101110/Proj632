<?php
//Réinitialisation de la session
if (isset($_SESSION)){
    $_SESSION["connecte"]=false;
    $_SESSION["identifiant"]="";
    echo "<script>window.location.href='?page=connexion'</script>";
}
else{
    echo "<script>window.location.href='?page=connexion'</script>";
}
?>