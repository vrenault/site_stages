<?php

class Clef_IHM {

    /**
     * Afficher un formulaire permettant de définir une nouvelle valeur de clef
     * sachant que la clé actuelle est affichée pour information
     * @param string $HClef Le condensat de la clé actuelle
     */
    public static function afficherFormulaireDefinitionClef($HClef) {
	?>
	<script type="text/javascript">
	    var auchargement = function() {
		// Si la clef est définie alors la sauvegarder sur le poste gestionnaire
		var clef = '<?php echo $_POST["clef"]; ?>';
		if (clef !== '') {
		    localStorage.setItem('clef', clef);
		}

		if (!localStorage.getItem('clef')) {
		    // Pas de stockage sur le poste gestionnaire
		    var cleActuelle = document.getElementById('clefactuelle');
		    document.getElementById('clefactuelle').value = 'clef pas encore définie';
		    document.getElementById('condensat').value = 'condensat pas encore calculé';
		} else {
		    // Stockage sur le poste gestionnaire existant
		    document.getElementById('clefactuelle').value = localStorage.getItem('clef');
		    document.getElementById('condensat').value = '<?php echo $HClef; ?>';
		}

		// Rendre les deux champs non modifiables
		document.getElementById('condensat').readOnly = true;
		document.getElementById('clefactuelle').readOnly = true;
	    };
	</script>
	<form method="post" action="">
	    <table>
		<tr id="entete2">
		    <td colspan="2">Définir une nouvelle clef</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr>
		    <th width="100">Clef actuelle</th>
		    <td>
			<input id="clefactuelle" type="text"/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input id="condensat" type="text"/>
		    </td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr>
		    <th width="100">Nouvelle clef</th>
		    <td>
			<input type="text" name="clef" value=""/>
		    </td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr>
		    <td colspan="2">
			<input type="submit" name="genere" value="Générer le condensat"/>
		    </td>
		</tr>
	    </table>
	</form>
	<?php
    }

    /**
     * Afficher la valeur de la clée actuelle est la valeur de son condensat
     * @param string $HClef Le condensat de la clé actuelle
     */
    public static function afficherClef($HClef) {
	?>
	<script type="text/javascript">
	    var auchargement = function() {
		if (!localStorage.getItem('clef')) {
		    // Pas de stockage sur le poste gestionnaire
		    var cleActuelle = document.getElementById('clefactuelle');
		    document.getElementById('clefactuelle').value = 'clef pas encore définie';
		    document.getElementById('condensat').value = 'condensat pas encore calculé';
		} else {
		    // Stockage sur le poste gestionnaire existant
		    document.getElementById('clefactuelle').value = localStorage.getItem('clef');
		    document.getElementById('condensat').value = '<?php echo $HClef; ?>';
		}

		// Rendre les deux champs non modifiables
		document.getElementById('condensat').readOnly = true;
		document.getElementById('clefactuelle').readOnly = true;
	    };
	</script>
	<br/>
	<form method="post" action="">
	    <table>
		<tr id="entete2">
		    <td colspan="2">Rappel de la clef actuelle</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr>
		    <th width="100">Clef actuelle</th>
		    <td>
			<input id="clefactuelle" type="text"/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input id="condensat" type="text"/>
		    </td>
		</tr>
	    </table>
	</form>
	<?php
    }
}

?>