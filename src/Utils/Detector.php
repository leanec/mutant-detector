<?php

namespace App\Utils;

class Detector {
    
    /** @var int SAME_CHARS_ON_SEQ Cantidad de caracteres iguales para considerar la secuencia. */
    private const SAME_CHARS_ON_SEQ = 4;

    /** @var int SEQUENCES_TO_MUTANT Cantidad de secuencias necesarias para ser mutante. */
    private const SEQUENCES_TO_MUTANT = 2;

    /**
     * Analiza un DNA para comprobar si es mutante
     * 
     * @param array $dna
     *
     * @return bool
     */
    public static function checkDna($dna) {
        /** @var int $sequenceCount Contador de secuencias detectadas. */
        $sequenceCount = 0;
        /** @var int $dnaSize Longitud de una cadena de ADN. */
        $dnaSize = sizeof($dna);

        $rowNum = 0;
        foreach ($dna as $fila) {	
            $colNum = 0;
            foreach (str_split($fila) as $valor) {
                $bases[$rowNum][$colNum] = $valor;

                if ($colNum + 1 >= Detector::SAME_CHARS_ON_SEQ && Detector::checkHorizontal($bases, $rowNum, $colNum, 1)) {
                    $sequenceCount++;
                    if ($sequenceCount === Detector::SEQUENCES_TO_MUTANT) {
                        return true;
                    }
                }

                if ($rowNum + 1 >= Detector::SAME_CHARS_ON_SEQ && Detector::checkVertical($bases, $rowNum, $colNum, 1)) {
                    $sequenceCount++;
                    if ($sequenceCount === Detector::SEQUENCES_TO_MUTANT) {
                        return true;
                    }
                }

                if ($rowNum + 1 >= Detector::SAME_CHARS_ON_SEQ && $colNum + 1 >= 4 && Detector::checkMainDiagonal($bases, $rowNum, $colNum, 1)) {
                    $sequenceCount++;
                    if ($sequenceCount === Detector::SEQUENCES_TO_MUTANT) {
                        return true;
                    }
                }

                if ($rowNum + 1 >= Detector::SAME_CHARS_ON_SEQ && $colNum <= $dnaSize - 4 && Detector::checkAntiDiagonal($bases, $rowNum, $colNum, 1)) {
                    $sequenceCount++;
                    if ($sequenceCount === Detector::SEQUENCES_TO_MUTANT) {
                        return true;
                    }
                }

                $colNum++;
            }
            $rowNum++;
        }
        return false;
    }

    /**
     * Verifica recursivamente (hacia atrás) si hay una secuencia horizontal
     *
     * @param array $bases
     * @param int $rowNum
     * @param int $colNum
     * @param int $sameCharCount
     *
     * @return bool
     */
    private static function checkHorizontal(&$bases, $rowNum, $colNum, $sameCharCount)
    {
        if (isset($bases[$rowNum][$colNum-1])) {
            if ($bases[$rowNum][$colNum] === $bases[$rowNum][$colNum-1]) {
                $sameCharCount++;
                if ($sameCharCount === Detector::SAME_CHARS_ON_SEQ) {
                    return true;
                }
                return Detector::checkHorizontal($bases, $rowNum, $colNum-1, $sameCharCount);
            }
        }
        return false;
    }

    /**
     * Verifica recursivamente (hacia atrás) si hay una secuencia vertical
     *
     * @param array $bases
     * @param int $rowNum
     * @param int $colNum
     * @param int $sameCharCount
     *
     * @return bool
     */
    private static function checkVertical(&$bases, $rowNum, $colNum, $sameCharCount)
    {
        if (isset($bases[$rowNum-1][$colNum])) {
            if ($bases[$rowNum][$colNum] === $bases[$rowNum-1][$colNum]) {
                $sameCharCount++;
                if ($sameCharCount === Detector::SAME_CHARS_ON_SEQ) {
                    return true;
                }
                return Detector::checkVertical($bases, $rowNum-1, $colNum, $sameCharCount);
            }
        }
        return false;
    }

    /**
     * Verifica recursivamente (hacia atrás) si hay una secuencia en las diagonales
     *
     * @param array $bases
     * @param int $rowNum
     * @param int $colNum
     * @param int $sameCharCount
     *
     * @return bool
     */
    private static function checkMainDiagonal(&$bases, $rowNum, $colNum, $sameCharCount)
    {
        if (isset($bases[$rowNum-1][$colNum-1])) {
            if ($bases[$rowNum][$colNum] === $bases[$rowNum-1][$colNum-1]) {
                $sameCharCount++;
                if ($sameCharCount === Detector::SAME_CHARS_ON_SEQ) {
                    return true;
                }
                return Detector::checkMainDiagonal($bases, $rowNum-1, $colNum-1, $sameCharCount);
            }
        }
        return false;
    }

    /**
     * Verifica recursivamente (hacia atrás) si hay una secuencia en las antidiagonales
     *
     * @param array $bases
     * @param int $rowNum
     * @param int $colNum
     * @param int $sameCharCount
     *
     * @return bool
     */
    private static function checkAntiDiagonal(&$bases, $rowNum, $colNum, $sameCharCount)
    {
        if (isset($bases[$rowNum-1][$colNum+1])) {
            if ($bases[$rowNum][$colNum] === $bases[$rowNum-1][$colNum+1]) {
                $sameCharCount++;
                if ($sameCharCount === Detector::SAME_CHARS_ON_SEQ) {
                    return true;
                }
                return Detector::checkAntiDiagonal($bases, $rowNum-1, $colNum+1, $sameCharCount);
            }
        }
        return false;
    }
}