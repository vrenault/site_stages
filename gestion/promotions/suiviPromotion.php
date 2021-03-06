<?php

/**
 * Page suiviPromotion.php
 * Utilisation : page de suivi des étudiants d'une promotion
 * Dépendance(s) : suiviPromotionData.php --> traitement des requêtes Ajax
 * Accès : restreint par authentification HTTP
 */

include_once("../../classes/bdd/connec.inc");

include_once('../../classes/moteur/Utils.php');
spl_autoload_register('Utils::my_autoloader_from_level2');

// ---------------------
// Contrôleur de la page

// Demande remise à l'état par défaut du statut des étudiants de la promotion
if (isset($_POST['reset']) &&
    isset($_POST['annee']) && is_numeric($_POST['annee']) &&
    isset($_POST['filiere']) && is_numeric($_POST['filiere']) &&
    isset($_POST['parcours']) && is_numeric($_POST['parcours'])) {

    // Prise en compte des paramètres
    $filtres = array();

    array_push($filtres, new FiltreNumeric("anneeuniversitaire", $_POST['annee']));
    array_push($filtres, new FiltreNumeric("idparcours", $_POST['parcours']));
    array_push($filtres, new FiltreNumeric("idfiliere", $_POST['filiere']));

    $filtre = $filtres[0];

    for ($i = 1; $i < sizeof($filtres); $i++)
	$filtre = new Filtre($filtre, $filtres[$i], "AND");

    // Reset du statut de tous les étudiants de la promotion
    $tabEtudiants = Promotion::listerEtudiants($filtre);

    foreach ($tabEtudiants as $oEtudiant) {
	$oEtudiant->setCodeEtudiant("0");
	Etudiant_BDD::sauvegarder($oEtudiant);
    }
}

// Demande modification du statut des étudiants modifiés
if (isset($_POST['valider']) && isset($_POST['statut'])) {
    foreach ($_POST['statut'] as $key => $value) {
	$oEtudiant = Etudiant::getEtudiant($key);
	$oEtudiant->setCodeEtudiant($value);
	Etudiant_BDD::sauvegarder($oEtudiant);
    }
}

// --------------------
// Affichage de la page

$tabLiens = array();
$tabLiens[0] = array('../../', 'Accueil');
$tabLiens[1] = array('../', 'Gestion de la base');

IHM_Generale::header("Suivi de la", "promotion", "../../", $tabLiens);
Promotion_IHM::afficherFormulaireRecherche("suiviPromotionData.php", false);

// Affichage des données
echo "<div id='data'>";
include_once("suiviPromotionData.php");
echo "</div>";
?>

<?php

deconnexion();
IHM_Generale::endHeader(false);
IHM_Generale::footer("../../");

?>
