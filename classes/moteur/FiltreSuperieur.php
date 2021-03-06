<?php

/**
 * Classe FiltreSuperieur : un filtre de comparaison entre deux valeurs
 */

class FiltreSuperieur extends Filtre {

    /**
     * Constructeur d'un filtre '>='
     * @param string $champ
     * @param string $valeur
     */
    public function __construct($champ, $valeur) {
	$this->strFiltres = $champ . " >= '" . $valeur . "'";
    }

}

?>