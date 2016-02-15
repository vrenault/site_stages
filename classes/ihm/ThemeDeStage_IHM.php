<?php
$chemin = "../../classes/";
include_once($chemin."bdd/connec.inc");
include_once($chemin."bdd/ThemeDeStage_BDD.php");
include_once($chemin."ihm/IHM_Generale.php");
include_once($chemin."ihm/ThemeDeStage_IHM.php");
include_once($chemin."moteur/ThemeDeStage.php");

	class ThemeDeStage_IHM {
		
		public static function afficherFormulaireSaisie() {
			?>
			<FORM METHOD="POST" ACTION="">
            <table id="table_saisieTheme">
                <tr><td colspan=2>
                        <table id="presentation_saisieTheme">
                            <tr id="entete2">
                                <td colspan=2>Saisir un thème de stage</td>
                                <td><input type="text" name="theme" ></td>
                            </tr>
                            <tr>
                                <td colspan=2><input type=submit value="Enregistrer le thème"/><input type=reset value="Effacer"/></td>
                            </tr>
                        </table>
            </table>
        </FORM>
			
			<?php
		}


		public static function afficherFormulaireModification(){
			?>
			<FORM id="formModifTheme" METHOD="POST" ACTION="">
            <table id="table_msTheme">
                <tr><td colspan=2>
                        <table id="presentation_msTheme">
                            <tr id="entete2">
                                <td colspan=2>Modifier/Supprimer un thème de stage</td>
                            </tr>
                            <tr>
                                <th width="220">Sélectionnez le thème : </th>
                                <th>
                                    <?php
                                    $tabTheme = ThemeDeStage::getListeTheme();
                                    echo "<select name=theme>";
                                    echo "<option  value='-1' selected></option>";

                                    for ($i = 0; $i < sizeof($tabTheme); $i++) {

                                        echo $tabTheme[0]->getIdTheme();


                                    	echo "<option value='".$tabTheme[$i]->getIdTheme()."'>".$tabTheme[$i]->getTheme()."</option>";
                                    }


                                    echo "</select>";
                                    ?>
                                </th>
                            </tr>
                            <tr>
                                <td colspan=2>
                                    <input type=submit value="Modifier un thème" />
                                    <input type=submit value="Supprimer un thème" onclick="this.form.action='../../gestion/conventions/sup_themeDeStage.php'"/>
                                </td>
                            </tr>
                        </table>
            </table>
        	</FORM>
        	<?php
		}

        public static function afficherFormulaireGestion(){
            ?>
            <FORM id="formGestionTheme" METHOD="POST" ACTION="">
            <table id="table_gTheme">
                <tr><td colspan=2>
                        <table id="presentation_gTheme">
                            <tr id="entete2">
                                <td colspan=2>Ajouter, modifer ou supprimer un thème de stage</td>
                            </tr>
                            <tr>
                                <th width="220">Sélectionnez un thème : </th>
                                <th>
                                    <?php
                                    $tabTheme = ThemeDeStage::getListeTheme();
                                    echo "<select name=theme>";
                                    echo "<option  value='-1' selected>--------------------</option>";

                                    for ($i = 0; $i < sizeof($tabTheme); $i++) {
                                        echo $tabTheme[0]->getIdTheme();
                                        echo "<option value='".$tabTheme[$i]->getIdTheme()."'>".$tabTheme[$i]->getTheme()."</option>";
                                    }
                                    echo "</select>";
                                    ?>
                                </th>
                            </tr>
                            <tr>
                                <td colspan=2>
                                    <input type=submit value="Ajouter"/>
                                    <input type=submit value="Modifier"/>
                                    <input type=submit value="Supprimer" onclick="this.form.action='../../gestion/conventions/sup_themeDeStage.php'"/>
                                </td>
                            </tr>
                        </table>
            </table>
            </FORM>
            <?php
        }
	}
?>