<?php

/**
 * Représentation et accès à la table n°17 : les soutenances de stages
 */

class Soutenance_BDD {

    /**
     * Enregistrer ou mettre à jour un objet Soutenance
     * @global resource $db Référence sur la base ouverte
     * @global string $tab17 Nom de la table 'soutenances'
     * @param Soutenance $soutenance L'objet concerné
     * @return integer Identifiant de l'enregistrement
     */
    public static function sauvegarder($soutenance) {
	global $db;
	global $tab17;

	$heuredebut = $soutenance->getHeureDebut();
	$mindebut = $soutenance->getMinuteDebut();
	$ahuitclos = $soutenance->isAHuisClos();
	$iddatesoutenance = $soutenance->getDateSoutenance()->getIdentifiantBDD();
	$idsalle = $soutenance->getSalle()->getIdentifiantBDD();

	// Permet de vérifier si la Convention existe déjà dans la BDD
	if ($soutenance->getIdentifiantBDD() == "") {
	    // Création de la soutenance
	    $requete = "INSERT INTO $tab17(heuredebut, mindebut, ahuitclos, iddatesoutenance, idsalle)
			VALUES ('$heuredebut', '$mindebut', '$ahuitclos', '$iddatesoutenance', '$idsalle')";
	    $db->query($requete);

	    // Chercher l'id de la soutenance
	    $sql = "SELECT LAST_INSERT_ID() AS ID FROM $tab17";
	    $req = $db->query($sql);
	    $result = mysqli_fetch_array($req);
	    $soutenance->setIdentifiantBDD($result['ID']);
	} else {
	    // Mise à jour de la soutenance
	    $requete = "UPDATE $tab17
			SET heuredebut = '$heuredebut',
			    mindebut = '$mindebut',
			    ahuitclos = '$ahuitclos',
			    iddatesoutenance = '$iddatesoutenance',
			    idsalle = '$idsalle'
			WHERE idsoutenance = " . $soutenance->getIdentifiantBDD();

	    $db->query($requete);
	}

	// Retourner l'id de la soutenance
	return $soutenance->getIdentifiantBDD();
    }

    /**
     * Suppression d'un enregistrement Soutenance
     *
     * Du fait de la contrainte d'intégrité référentielle, la table 'convention'
     * est mise à jour automatiquement
     *
     * Du fait de la contrainte d'intégrité référentielle, la table 'convocation'
     * est mise à jour automatiquement
     *
     *
     * @global resource $db Référence sur la base ouverte
     * @global string $tab17 Nom de la table 'soutenances'
     * @param integer $id L'identifiant de la soutenance
     */
    public static function supprimer($id) {
	global $db;
	global $tab17;

	$requete = "DELETE FROM $tab17 WHERE idsoutenance='$id'";
	$db->query($requete);
    }

    /**
     * Obtenir un enregistrement Soutenance à partir de son identifiant
     * @global resource $db Référence sur la base ouverte
     * @global string $tab17 Nom de la table 'soutenances'
     * @param integer $id Identifiant
     * @return enregistrement
     */
    public static function getSoutenance($id) {
	global $db;
	global $tab17;

	$requete = "SELECT * FROM $tab17 WHERE idsoutenance='$id'";
	$convention = $db->query($requete);
	return mysqli_fetch_array($convention);
    }

    /**
     * Obtenir la convention associée à une soutenance
     * @global resource $db Référence sur la base ouverte
     * @global string $tab4 Nom de la table 'convention'
     * @param integer $idsoutenance Identifiant de la soutenance concernée
     * @return enregistrement
     */
    public static function getConvention($idsoutenance) {
	global $db;
	global $tab4;

	$requete = "SELECT * FROM $tab4 WHERE idsoutenance='$idsoutenance'";
	$convention = $db->query($requete);
	return mysqli_fetch_row($convention);
    }

    /**
     * Obtenir les enregistrements Soutenance à partir de la salle et de la date
     * @global resource $db Référence sur la base ouverte
     * @global string $tab17 Nom de la table 'soutenances'
     * @param integer $idsalle Identifiant de la salle
     * @param integer $iddate Identifiant de la date de soutenance
     * @return tableau d'enregistrements
     */
    public static function listerSoutenanceFromSalleAndDate($idsalle, $iddate) {
	global $db;
	global $tab17;

	$requete = "SELECT * FROM $tab17 WHERE iddatesoutenance='$iddate' AND idsalle='$idsalle'";
	return $db->query($requete);
    }

    /**
     * Obtenir tous les enregistrements Soutenance d'une année donnée
     * @global resource $db Référence sur la base ouverte
     * @global type $tab5 Nom de la table 'datesoutenance'
     * @global string $tab17 Nom de la table 'soutenances'
     * @param integer $annee L'année concernée
     * @return tableau d'enregistrements
     */
    public static function listerSoutenanceFromAnnee($annee) {
	global $db;
	global $tab5;
	global $tab17;

	$requete = "SELECT $tab17.idsoutenance, $tab17.heuredebut, $tab17.mindebut,
			   $tab17.ahuitclos, $tab17.iddatesoutenance, $tab17.idsalle
		    FROM $tab17, $tab5
		    WHERE $tab17.iddatesoutenance = $tab5.iddatesoutenance AND
			  $tab5.annee='$annee'";

	return $db->query($requete);
    }

}

?>