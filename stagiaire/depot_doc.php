<?php

$chemin = "../classes/";

include_once($chemin."bdd/connec.inc");
include_once($chemin."moteur/Utils.php");
include_once($chemin."ihm/IHM_Generale.php");
include_once($chemin."ihm/Etudiant_IHM.php");
include_once($chemin."ihm/Promotion_IHM.php");
include_once($chemin."moteur/SujetDeStage.php");
include_once($chemin."moteur/Promotion.php");
include_once($chemin."moteur/Filiere.php");
include_once($chemin."moteur/Etudiant.php");
include_once($chemin."moteur/Filtre.php");
include_once($chemin."moteur/FiltreString.php");
include_once($chemin."moteur/Parcours.php");
include_once($chemin."moteur/Parrain.php");
include_once($chemin."moteur/Convention.php");
include_once($chemin."moteur/Contact.php");
include_once($chemin."moteur/Entreprise.php");
include_once($chemin."bdd/Etudiant_BDD.php");
include_once($chemin."bdd/Filiere_BDD.php");
include_once($chemin."bdd/Parcours_BDD.php");
include_once($chemin."bdd/Promotion_BDD.php");
include_once($chemin."bdd/Parrain_BDD.php");
include_once($chemin."bdd/Convention_BDD.php");
include_once($chemin."bdd/Contact_BDD.php");
include_once($chemin."bdd/Entreprise_BDD.php");

header ("Content-type:text/html; charset=utf-8");

$tabLiens = array();
$tabLiens[0] = array('../', 'Accueil');
$tabLiens[1] = array('./', 'Stagiaire');
IHM_Generale::header("Dépôt de", "documents", "../", $tabLiens);

Promotion_IHM::afficherFormulaireRecherche("depot_docData.php", false);

//Envoie d'un mail de notification au parrain et au responsable
function envoyerNotification($oEtudiant, $annee, $idFiliere, $idParcours, $idParrain, $nomFichier, $typedocument){
	global $emailResponsable;
	global $baseSite;

	$oParrain = Parrain::getParrain($idParrain);

	$oPromotion = Promotion::getPromotionFromParcoursAndFiliere($annee, $idFiliere, $idParcours);
	$oConvention = Convention::getConventionFromEtudiantAndPromotion($oEtudiant->getIdentifiantBDD(), $oPromotion->getIdentifiantBDD());

	$headers = 'Content-Type:  text/html; charset="iso-8859-1"'."\n";
	$headers .= 'Content-Transfer-Encoding: 8bit'."\n";
	$headers .= 'From: '.$emailResponsable."\n";
	$headers .= 'Reply-To: '.$emailResponsable."\n";
	$headers .= 'X-Mailer: PHP/'.phpversion();

	$msg = "Ceci est un message automatique concernant le suivi de stage.<br/>
		Un $typedocument a été déposé sur le site des stages.<br/>
		<br/>
		Etudiant : ".$oEtudiant->getNom()." ".$oEtudiant->getPrenom()."<br/>
		Diplôme : ".$oPromotion->getFiliere()->getNom()." ".$oPromotion->getParcours()->getNom()."<br/>
		Entreprise : ".$oConvention->getContact()->getEntreprise()->getNom()."<br/>
		Document : $typedocument <a href='".$baseSite."documents/".$nomFichier."'>accessible ici</a><br/>
		<br/>
		Bonne lecture<br/>
		Le responsable des stages";

	mail($oParrain->getEmail().",".$emailResponsable.",".$oEtudiant->getEmailInstitutionel(), "Site des stages : $typedocument déposé", $msg, $headers);
}

//Fonction pour copier le rapport sur le serveur
function depotRapport($etudiant, $annee, $filiere) {
	$nomFichier="";
	$erreur=false;
	$file = $_FILES['uploadRapport']['name'];
	$type = $_FILES['uploadRapport']['type'];
	$size = $_FILES['uploadRapport']['size'];
	$temp = $_FILES['uploadRapport']['tmp_name'];

	$filename = explode(".",$_FILES['uploadRapport']['name']);
	if (sizeof($filename) != 0)
		$extension = $filename[sizeof($filename) - 1];

	if ($file && ($extension=="pdf" || $extension=="doc" || $extension=="docx")) {
		$file_size_max = 20000000; //en bytes

		$store_dir = "../documents/rapports/";

		$accept_overwrite = true;

		$nomFiliere = Filiere::getFiliere($filiere)->getNom();
		$annees = ($annee - 2000).($annee - 2000 + 1);

		$nomFichier = $etudiant->getIdentifiantBDD() ."_". $nomFiliere ."_". Utils::removeaccents($etudiant->getNom()) ."_". Utils::removeaccents($etudiant->getPrenom()) ."_". $annees .".". $extension;

		if ($size > $file_size_max) {
			IHM_Generale::erreur("Désolé, votre fichier est trop volumineux (supérieur à 20 Mo) !");
			$erreur=true;
		} else if (file_exists($store_dir.$nomFichier) && ($accept_overwrite)) {
			unlink($store_dir.$nomFichier);
			if (!@move_uploaded_file($_FILES['uploadRapport']['tmp_name'],$store_dir.$nomFichier)) {
				$erreur=true;
				IHM_Generale::erreur("Désolé mais le dépôt a échoué !");
			}
		} else if (!@move_uploaded_file($_FILES['uploadRapport']['tmp_name'],$store_dir.$nomFichier)) {
			$erreur=true;
			IHM_Generale::erreur("Le dépôt de fichier a échoué !");
		}
	} else {
		IHM_Generale::erreur("Vous n'avez donné aucun nom de fichier ou l'extension n'est peut-être pas acceptée !!");
	}

	if ($erreur) {
		$nomFichier="";
	}

	return $nomFichier;
}

//Fonction pour copier les résumés sur le serveur
function depotResume($etudiant, $annee, $filiere) {
	$nomFichier="";
	$erreur=false;
	$file = $_FILES['uploadResume']['name'];
	$type = $_FILES['uploadResume']['type'];
	$size = $_FILES['uploadResume']['size'];
	$temp = $_FILES['uploadResume']['tmp_name'];

	$filename = explode(".",$_FILES['uploadResume']['name']);
	if (sizeof($filename) != 0)
		$extension = $filename[sizeof($filename) - 1];

	if ($file && ($extension=="pdf" || $extension=="doc" || $extension=="docx")) {
		$file_size_max = 20000000; //en bytes

		$store_dir = "../documents/resumes/";

		$accept_overwrite = true;

		$nomFiliere = Filiere::getFiliere($filiere)->getNom();
		$annees = ($annee - 2000).($annee - 2000 + 1);

		$nomFichier = $etudiant->getIdentifiantBDD() ."_". $nomFiliere ."_". Utils::removeaccents($etudiant->getNom()) ."_". Utils::removeaccents($etudiant->getPrenom()) ."_". $annees .".". $extension;

		if ($size > $file_size_max){
			IHM_Generale::erreur("Désolé, votre sujet de stage est trop volumineux (supérieur à 20 Mo) !");
			$erreur=true;
		} else if (file_exists($store_dir.$nomFichier) && ($accept_overwrite)) {
			unlink($store_dir.$nomFichier);
			if (!@move_uploaded_file($_FILES['uploadResume']['tmp_name'],$store_dir.$nomFichier)) {
				$erreur=true;
				IHM_Generale::erreur("Désolé mais le dépôt a échoué !");
			}
		} else if (!@move_uploaded_file($_FILES['uploadResume']['tmp_name'],$store_dir.$nomFichier)) {
				$erreur=true;
				IHM_Generale::erreur("Le dépôt de fichier a échoué !");
		}
	  } else {
		IHM_Generale::erreur("Vous n'avez donné aucun nom de fichier ou l'extension n'est peut-être pas acceptée !!");
	  }

	  if ($erreur) {
	  	$nomFichier="";
	  }
	  return $nomFichier;
}

// Affichage des données
echo "<div id='data'>\n";
include_once("depot_docData.php");
echo "\n</div>";

// Dépôt d'un rapport
if (isset($_POST['submitRapport'])) {
	if (isset($_FILES['uploadRapport']['name']) && $_FILES['uploadRapport']['name'] != "") { //si un fichier est envoyé
		$etudiant = Etudiant::getEtudiant($_POST['idEtudiant']);
		$filename = depotRapport($etudiant, $_POST['annee'], $_POST['filiere']);
		if($filename!=""){
			envoyerNotification($etudiant, $_POST['annee'], $_POST['filiere'], $_POST['parcours'], $_POST['idParrain'], "rapports/".$filename, "rapport de stage");
			echo "<p>Votre rapport de stage a été enregistré et votre référent a été informé de ce dépôt.</p>";
		}
	} else {
		IHM_Generale::erreur("Vous devez spécifier un fichier !");
	}
}

// Dépôt d'un résumé
if (isset($_POST['submitResume'])) {
	if(isset($_FILES['uploadResume']['name']) && $_FILES['uploadResume']['name'] != ""){ //si un fichier est envoyé
		$etudiant = Etudiant::getEtudiant($_POST['idEtudiant']);
		$filename = depotResume($etudiant, $_POST['annee'], $_POST['filiere']);
		if ($filename!="") {
			envoyerNotification($etudiant,$_POST['annee'], $_POST['filiere'], $_POST['parcours'], $_POST['idParrain'], "resumes/".$filename, "résumé de stage");
			echo "<p>Votre résumé de stage a été enregistré et votre référent a été informé de ce dépôt.</p>";
		}
	} else {
		IHM_Generale::erreur("Vous devez spécifier un fichier !");
	}
}

deconnexion();
IHM_Generale::endHeader(false);
IHM_Generale::footer("../");

?>