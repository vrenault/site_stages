<?php

$chemin = "../../classes/";

include_once($chemin . "bdd/connec.inc");
include_once($chemin . "ihm/IHM_Generale.php");

include_once($chemin . "ihm/Clef_IHM.php");
include_once($chemin . "moteur/Clef.php");

// ----------------------------------------------------------------------------
// Contrôleur
if (isset($_POST['genere']) && isset($_POST['clef']) && $_POST['clef'] != '') {
    // Génère le condensat
    $HClef = Clef::calculCondensat($_POST['clef']);

    // Sauvegarde sur fichier le condensat
    $f = fopen('../../documents/demon/clef', 'w');
    fwrite($f, $HClef);
    fclose($f);
}

// Récupérer le condensat de la clef
$f = fopen('../../documents/demon/clef', 'r');
$HClef = fread($f, 100);
fclose($f);

// ----------------------------------------------------------------------------
// Affichage

$tabLiens = array();
$tabLiens[0] = array('../../', 'Accueil');
$tabLiens[1] = array('../', 'Gestion des stages');

IHM_Generale::header("Gestion de la", "clef d'accès", "../../", $tabLiens, "auchargement");

// Vérification de la période pour exécuter cette page
$mois = date('n');

if ($mois == 9 || $mois == 10) { // Il faut être entre le 1/09 et le 31/10
    // Afficher formulaire pour définir une clef
    Clef_IHM::afficherFormulaireDefiniClef($HClef);
} else {
    // Afficher un message d'erreur
    IHM_Generale::erreur("Cette fonctionnalité n'est accessible que durant le mois de septembre et le mois d'octobre.");
    // Afficher un rappel de la clé actuelle
    Clef_IHM::afficherClef($HClef);
}

IHM_Generale::endHeader(false);
IHM_Generale::footer("../../");

?>