<?php
setcookie('pseudo', $_POST['pseudo'], time() + 365*24*3600, null, null, false, true); 
echo $_COOKIE['pseudo']; 
// Connexion à la bdd
include 'connexion.php';

// Récupération des variables
$pseudo = $_POST['pseudo'];
$msg = $_POST['message'];

// On vérifie si une variable est définie
if (isset($_POST['pseudo']) && isset($_POST['message'])) {
   // $bdd -> query("INSERT INTO minichat(pseudo, message) VALUES('elo', 'salut les gens !') ");
    $req = $bdd -> prepare ("INSERT INTO minichat(pseudo, message) VALUES(:pseudo, :msg) ") or die(print_r($bdd->errorInfo()));
    $req -> execute(array(
        'pseudo' => $pseudo,
        'msg' => $msg
    ));
    echo 'Votre message a bien été envoyé';
}
$req->closeCursor(); // Termine le traitement de la requête  

// Puis redirection vers minichat.php comme ceci :
header('Location: minichat.php');
?>