<?php

class Salle_IHM {

    public static function afficherFormulaireSaisie() {
	?>
	<FORM METHOD="POST" ACTION="">
	    <table id="table_saisieSalle">
		<tr>
		    <td colspan=2>
			<table id="presentation_saisieSalle">
			    <tr id="entete2">
				<td colspan=2>Saisir une salle</td>
			    </tr>
			    <tr>
				<th width="100">Nom :</th>
				<td>
				    <input type="text" name="nom" >
				</td>
			    </tr>
			    <tr>
				<td colspan=2>
				    <input type=submit value="Enregistrer les données"/>
				    <input type=reset value="Effacer"/>
				</td>
			    </tr>
			</table>
		    </td>
		</tr>
	    </table>
	</FORM>
	<?php
    }

    public static function afficherFormulaireChoixSalle() {
	$tabSalle = Salle::listerSalle();
	?>
	<FORM METHOD="POST" ACTION="" name="sd">
	    <table id="table_modifierSalle">
		<tr>
		    <td colspan=2>
			<table id="presentation_modifierSalle">
			    <tr id="entete2">
				<td colspan=2>Choisir la salle</td>
			    </tr>
			    <tr>
				<th width="220">Sélectionnez la salle : </th>
				<th>
				    <?php
				    echo "<select name=salle>";
				    echo "<option  value='-1' selected></option>";
				    for ($i = 0; $i < sizeof($tabSalle); $i++) {
					echo "<option value='" . $tabSalle[$i]->getIdentifiantBDD() . "'
						      name='" . $tabSalle[$i]->getNom() . "'> " . $tabSalle[$i]->getNom() . "</option>";
				    }
				    echo "</select>";
				    ?>
				</th>
			    </tr>
			    <tr>
				<td colspan=2>
				    <input type=submit value="Modifier une salle" />
				    <input type=submit value="Supprimer une salle" onclick="this.form.action = '../../gestion/soutenances/supprimerSalle.php'"/>
				</td>
			    </tr>
			</table>
		    </td>
		</tr>
	    </table>
	</FORM>
	<?php
    }

    public static function afficherFormulaireModification($idSalle) {
	$salle = Salle::getSalle($idSalle);
	?>
	<form action='modSalle.php' method='post'>
	    <input type=hidden name='id' value=<?php echo $salle->getIdentifiantBDD(); ?>>
	    <table>
		<tr>
		    <td colspan="2">
			<table>
			    <tr id='entete2'>
				<td colspan="2">Modification d'une salle</td>
			    </tr>
			    <tr>
				<th>Nom : </th>
				<td>
				    <input name='nom' size=100 value='<?php echo $salle->getNom(); ?>'>
				</td>
			    </tr>
			    <tr>
				<td colspan="2">
				    <input type=submit value='Enregistrer les données'/>
				</td>
			    </tr>
			</table>
		</tr>
	    </table>
	</form>
	<?php
    }

    public static function afficherPlanningSalles($annee, $listeConvention) {
	$enteteTableau =
	"<table>
	    <tr id='entete'>
		<td rowspan='2' style='width: 85px;'>Horaires</td>
		<td colspan='2'>Étudiant</td>
		<td rowspan='2' style='width: 50px;'>Fiche de stage</td>
		<td colspan='2'>Jury</td>
		<td rowspan='2' style='width: 75px;'>Salle</td>
	    </tr>
	    <tr id='entete'>
		<td style='width: 100px;'>Nom prénom</td>
		<td style='width: 60px;'>Cycle</td>
		<td style='width: 110px;'>Référent</td>
		<td style='width: 110px;'>Examinateur</td>
	    </tr>";

	$finTableau = "</table>";
	echo '<table>';

	// Pour chaque convention
	$k = 0; $i = 0; $j = 0;
	foreach ($listeConvention as $convention) {
	    $soutenance = $convention->getSoutenance();

	    if ($j == 0) {
		echo $finTableau;
		echo $enteteTableau;
	    }

	    $j++; $k++;
	    $nomSalle = ($soutenance->getSalle()->getIdentifiantBDD() != 0) ? $soutenance->getSalle()->getNom() : "Non attribuée";
	    $etudiant = $convention->getEtudiant();
	    $promotion = $etudiant->getPromotion($annee);
	    $parcours = $promotion->getParcours();
	    $filiere = $promotion->getFiliere();
	    $parrain = $convention->getParrain();
	    $examinateur = $convention->getExaminateur();

	    // Gestion horaires
	    $tempsSoutenance = $filiere->getTempsSoutenance();
	    $heureDebut = $soutenance->getHeureDebut();
	    $minuteDebut = $soutenance->getMinuteDebut();
	    $heureFin = $heureDebut;
	    $minuteFin = ($minuteDebut + $tempsSoutenance);
	    if ($minuteFin > 59) {
		$minuteFin-=60;
		$heureFin++;
	    }
	    $minuteDebut = ($minuteDebut!=0) ? $minuteDebut : "00";
	    $minuteFin = ($minuteFin!=0) ? $minuteFin : "00";

	    // Incrementation
	    $i = ($i+1) % 2;

	    // Affichage
	    echo
	    "<tr id='ligne".$i."'>
		<td>".$heureDebut."h".$minuteDebut." / ".$heureFin."h".$minuteFin."</td>
		<td>".strtoupper($etudiant->getNom())." ".$etudiant->getPrenom()."</td>
		<td>".$filiere->getNom()." ".$parcours->getNom()."</td>
		<td><a href='fichedestage.php?idEtu=".$etudiant->getIdentifiantBDD()."&idPromo=".$promotion->getIdentifiantBDD()."' target='_blank'><img src=\"../images/resume.png\" /></a></td>
		<td>".strtoupper($parrain->getNom())." ".$parrain->getPrenom()."
		<td>".strtoupper($examinateur->getNom())." ".$examinateur->getPrenom()."
		<td>".$nomSalle."</td>
	    </tr>";
	}

	echo $finTableau;

	// S'il n'y a pas de conventions
	if ($k == 0)
	    echo "<br/><center>Il n'y a pas de soutenance associée à cette salle pour la date sélectionnée.</center>";
    }

    }
?>