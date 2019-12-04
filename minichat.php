<?php
if(isset($_POST['pseudo'])) {// Si le formulaire a été envoyé...

    setcookie('pseudo', $_POST['pseudo'], time() + 365*24*3600, null, null, false, true); // On créé le cookie, c'est IMPORTANT !
    header('Location: minichat.php'); // Et on actualise la page
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title> Mon MiniChat</title>
	</head>
	
	<body>

	
<!-- Création du formulaire -->

	<form method="post" action="minichat_post.php">
	<p>
		<label for="pseudo"> Ton Pseudo : </label>
<!--  Si le cookie existe, on écrit le pseudo dans le champ -->  
		<input type="text" name="pseudo" id="pseudo" maxlength="20" value="<?php if(isset($_COOKIE['pseudo'])) echo $_COOKIE['pseudo'];?>" />
		<br /><br />
		<label for="message"> Ton Message : </label> <br/>
		<textarea name="message" id="message" rows="5" cols="30">Ecrire votre message ici</textarea>
		<br />
		<input type="submit" value="Envoyer"  />
	</p>	
	</form>
	
<button id="refresh" onclick="document.location.reload(false)"> Rafraichir </button>

<br /><br />

<?php	
// Connection à la base de données
	
include 'connexion.php';

//Affichage de 10 messages par page
$msg_par_page = 5; 
//Nous récupérons le contenu de la requête dans $retour_total
$retour_total = $bdd -> prepare('SELECT COUNT(*) AS total FROM minichat'); 
$retour_total -> execute();
//On range le retour sous la forme d'un tableau
$donnees_total = $retour_total -> fetch();
//On récupère le total pour le placer dans la variable $total
$total = $donnees_total['total']; 
//Nous allons maintenant compter le nombre de pages
$nb_pages = ceil($total / $msg_par_page);

if(isset($_GET['page'])) {// Si la variable $_GET['page'] existe...
     $page_actuelle = intval($_GET['page']);
 
     if($page_actuelle > $nb_pages) { // Si la valeur de $page_actuelle (n°page) est plus grande que $nb_pages...
     
          $page_actuelle = $nb_pages;
     }
} else {
     $page_actuelle = 1; // La page actuelle est la n°1    
}

// On calcul la première entrée à lire
$premiere_entree = ($page_actuelle - 1) * $msg_par_page; 


//Récupération des 10 messages 10 par 10
/* On prépare la requête à son exécution. */
$reponse = $bdd -> prepare('SELECT pseudo, message FROM minichat ORDER BY ID DESC LIMIT :msg_par_page OFFSET :premiere_entree');
/* On lie ici une valeur à la requête, soit remplacer de manière sûre un marqueur par
 * sa valeur, nécessaire pour que la requête fonctionne. */
$reponse -> bindValue(
    'msg_par_page',         // Le marqueur est nommé «messagesParPage »
     $msg_par_page,         // Il doit prendre la valeur de la variable $messagesParPage
     PDO::PARAM_INT   // Cette valeur est de type entier
);
$reponse -> bindValue('premiere_entree', $premiere_entree, PDO::PARAM_INT);

/* Maintenant qu'on a lié la valeur à la requête, on peut l'exécuter */
$reponse -> execute();

//Affichage de chaque message
while ($donnees=$reponse->fetch())

	echo '<p><strong>' . htmlspecialchars($donnees['pseudo']) . '</strong> : ' . htmlspecialchars($donnees['message']) . '</p>';
	echo '<p align="center">Page : ';

for($i = 1; $i <= $nb_pages; $i++) {

     //On va faire notre condition
     if($i == $page_actuelle) {
         echo ' [ '.$i.' ] '; 
     } else {
          echo ' <a href="minichat.php?page='.$i.'">'.$i.'</a> ';
     }
}
echo '</p>';

//Cloturer la requête
$reponse->closeCursor();

?>
	
	</body>
</html>