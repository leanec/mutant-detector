<?php

namespace App\Controllers;

use App\Utils\Detector;

class Mutant {
    
    /**
     * Verifica si el DNA es mutante o no
     * 
     * @param array $dna
     *
     * @return bool
     */
    public static function isMutant($dna) {
        return Detector::checkDna($dna);
    }
}