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
        /** @var array $foundSequences Almacena posicion de secuencias detectadas para evitar superposiciones. */
        $foundSequences = [];
        /** @var array $bases Guarda las bases nitrogenadas formando una Matriz NxN. */
        $bases = [];
        for ($i = 0; $i < $dnaSize; $i++) {
            $bases[$i] = str_split($dna[$i]);
        }

        for ($i = 0; $i < $dnaSize; $i++) {
            $foundSequences['h'] = -1;
            $foundSequences['v'] = -1;
            for ($j = 0; $j < $dnaSize; $j++) {
                
                /*  HORIZONTAL
                    (1) Verifica que la celda ($j) de la fila actual ($i) no se superpone a alguna secuencia detectada
                        (representada en foundSequences['h'] = columna última celda de secuencia anterior).

                    (2) Verifica recursivamente retrocediendo en las columnas (restando 1 a la columna $j)
                        si existe una secuencia del largo requerido.
                    
                    Ej. para fila $i=0:
                        1.  Ingresa en i0j3 y recursivamente revisa las celdas i0j3, i0j2, i0j1, i0j0
                            Última secuencia detectada (TTTT)    =>    foundSequences['h'] = 3
                        2.  Vuelve a ingresar en i0j7 y recursivamente revisa las celdas i0j7, i0j6, i0j5, i0j4
                    ..............................................
                    :    : j0 : j1 : j2 : j3 : j4 : j5 : j6 : j7 :
                    :....:....:....:....:....:....:....:....:....:
                    : i0 :  T :  T :  T :  T :  . :  . :  . :  X :
                    : i1 :    :    :    :    :    :    :    :    :
                    : i2 :    :    :    :    :    :    :    :    :
                    : i3 :    :    :    :    :    :    :    :    :
                    : i4 :    :    :    :    :    :    :    :    :
                    : i5 :    :    :    :    :    :    :    :    :
                    : i6 :    :    :    :    :    :    :    :    :
                    : i7 :    :    :    :    :    :    :    :    :
                    :....:....:....:....:....:....:....:....:....:
                */
                if (
                    $j >= $foundSequences['h'] + Detector::SAME_CHARS_ON_SEQ && // (1)
                    Detector::checkHorizontal($bases, $i, $j, 1) // (2)
                ) {
                    $sequenceCount++;
                    $foundSequences['h'] = $j;
                    if ($sequenceCount === Detector::SEQUENCES_TO_MUTANT) {
                        return true;
                    }
                }

                /*  VERTICAL
                    (1) Verifica que la celda ($j) de la columna actual ($i) no se superpone a alguna secuencia detectada
                        (representada en foundSequences['v'] = fila última celda de secuencia anterior).
                    
                    (2) Verifica recursivamente retrocediendo en las filas (restando 1 a la fila $j)
                        si existe una secuencia del largo requerido.

                    Ej. para columna $i=0: 
                        1.  Ingresa en j3i0 y recursivamente revisa las celdas j3i0, j2i0, j1i0, j0i0
                            Última secuencia detectada (TTTT)    =>    foundSequences['v'] = 3
                        2.  Vuelve a ingresar en i0j7 y recursivamente revisa en la columna actual j7i0, j6i0, j5i0, j4i0
                    ..............................................
                    :    : i0 : i1 : i2 : i3 : i4 : i5 : i6 : i7 :
                    :....:....:....:....:....:....:....:....:....:
                    : j0 :  T :    :    :    :    :    :    :    :
                    : j1 :  T :    :    :    :    :    :    :    :
                    : j2 :  T :    :    :    :    :    :    :    :
                    : j3 :  T :    :    :    :    :    :    :    :
                    : j4 :  . :    :    :    :    :    :    :    :
                    : j5 :  . :    :    :    :    :    :    :    :
                    : j6 :  . :    :    :    :    :    :    :    :
                    : j7 :  X :    :    :    :    :    :    :    :
                    :....:....:....:....:....:....:....:....:....:
                */
                if (
                    $j >= $foundSequences['v'] + Detector::SAME_CHARS_ON_SEQ && // (1)
                    Detector::checkVertical($bases, $j, $i, 1) // (2)
                ) {
                    $foundSequences['v'] = $j;
                    $sequenceCount++;
                    if ($sequenceCount === Detector::SEQUENCES_TO_MUTANT) {
                        return true;
                    }
                }

                /*  DIAGONALES
                    (1) Verifica que la fila ($i) es mayor o igual a la cantidad de caracteres necesarios en secuencia,
                        si no lo es no es necesario buscar aún en esa diagonal (ya que revisa restando filas).

                    (2) Verifica que la celda ($j) de la fila actual ($i) no se superpone a alguna secuencia detectada
                        (representada en foundSequences['d'.($i-$j)] = columna última celda de secuencia anterior)
                        * Para la diagonal principal el índice de foundSequences es siempre 0, para las que estan por encima es negativo 
                        y para las que estan por debajo es positivo.
                    
                    (3) Verifica recursivamente retrocediendo en las filas y columnas (restando 1 a la columna $j y a la fila $i)
                        si existe una secuencia del largo requerido.

                    Ej. para diagonal principal ($i-$j)=0:
                        1.  Ingresa en i3j3 y recursivamente revisa las celdas i3j3, i2j2, i1j1, i4j4 
                            Última secuencia detectada (TTTT)    =>    foundSequences['d0'] = 3   
                        2.  Vuelve a ingresar en i7j7 y recursivamente revisa i7j7, i6j6, i5j5, i4j4          
                    ..............................................
                    :    : j0 : j1 : j2 : j3 : j4 : j5 : j6 : j7 :
                    :....:....:....:....:....:....:....:....:....:
                    : i0 :  T :    :    :    :    :    :    :    :
                    : i1 :    :  T :    :    :    :    :    :    :
                    : i2 :    :    :  T :    :    :    :    :    :
                    : i3 :    :    :    :  T :    :    :    :    :
                    : i4 :    :    :    :    :  . :    :    :    :
                    : i5 :    :    :    :    :    :  . :    :    :
                    : i6 :    :    :    :    :    :    :  . :    :
                    : i7 :    :    :    :    :    :    :    :  X :
                    :....:....:....:....:....:....:....:....:....:
                */
                if ($i + 1 >= Detector::SAME_CHARS_ON_SEQ && // (1)
                    $j >= (isset($foundSequences['d'.($i-$j)]) ? $foundSequences['d'.($i-$j)] : -1) + Detector::SAME_CHARS_ON_SEQ && // (2)
                    Detector::checkMainDiagonal($bases, $i, $j, 1) // (3)
                ) {
                    $foundSequences['d'.($i-$j)] = $j;
                    $sequenceCount++;
                    if ($sequenceCount === Detector::SEQUENCES_TO_MUTANT) {
                        return true;
                    }
                }

                /*  ANTIDIAGONALES
                    (1) Verifica que la columna ($j) es mayor o igual a la cantidad de caracteres necesarios en secuencia,
                        si no lo es no es necesario buscar en esa antidiagonal (ya que revisa restando columnas).
                    
                    (2) Verifica que la fila ($i) es menor al tamaño menos la cantidad de caracteres necesarios en secuencia,
                        si no lo es no es necesario buscar en esa antidiagonal (ya que revisa sumando filas).

                    (3) Verifica que la celda ($j) de la fila actual ($i) no se superpone a alguna secuencia detectada
                        (representada en foundSequences['ad'.($i+$j)] = columna última celda de secuencia anterior)
                        * Para la antidiagonal principal el índice de foundSequences es siempre dnaSize, 
                        para las que estan por encima es menor y para las que estan por debajo es mayor.
                    
                    (4) Verifica recursivamente avanzando en las filas y retrocediendo en las columnas 
                        (sumando 1 a la fila $i y restando 1 a la columna $j) si existe una secuencia del largo requerido.

                    Ej. para antidiagonal principal ($i+$j)=7:
                        1.  Ingresa en i0j7 y recursivamente revisa las celdas i0j7, i1j6, i2j5, i3j4
                            Última secuencia detectada (TTTT)    =>    foundSequences['ad7'] = 3
                        2.  Vuelve a ingresar en i4j3 y recursivamente revisa i4j3, i5j2, i6j1, i7j0     
                    ..............................................
                    :    : j0 : j1 : j2 : j3 : j4 : j5 : j6 : j7 :
                    :....:....:....:....:....:....:....:....:....:
                    : i0 :    :    :    :    :    :    :    :  T :
                    : i1 :    :    :    :    :    :    :  T :    :
                    : i2 :    :    :    :    :    :  T :    :    :
                    : i3 :    :    :    :    :  T :    :    :    :
                    : i4 :    :    :    :  X :    :    :    :    :
                    : i5 :    :    :  . :    :    :    :    :    :
                    : i6 :    :  . :    :    :    :    :    :    :
                    : i7 :  . :    :    :    :    :    :    :    :
                    :....:....:....:....:....:....:....:....:....:
                */
                if (
                    $j + 1 >= Detector::SAME_CHARS_ON_SEQ && // (1)
                    $i <= $dnaSize - Detector::SAME_CHARS_ON_SEQ && // (2)
                    $j <= (isset($foundSequences['ad'.($i+$j)]) ? $foundSequences['ad'.($i+$j)] : $dnaSize) && // (3)
                    Detector::checkAntiDiagonal($bases, $i, $j, 1) // (4)
                ) {
                    $foundSequences['ad'.($i+$j)] = $j - Detector::SAME_CHARS_ON_SEQ;;
                    $sequenceCount++;
                    if ($sequenceCount === Detector::SEQUENCES_TO_MUTANT) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * Verifica recursivamente (restando a las columnas) si hay una secuencia horizontal
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
     * Verifica recursivamente (restando a la fila) si hay una secuencia vertical
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
     * Verifica recursivamente (restando a la columna y a la fila) si hay una secuencia en las diagonales
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
     * Verifica recursivamente (sumando a la fila y restando a la columna) si hay una secuencia en las antidiagonales
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
        if (isset($bases[$rowNum+1][$colNum-1])) {
            if ($bases[$rowNum][$colNum] === $bases[$rowNum+1][$colNum-1]) {
                $sameCharCount++;
                if ($sameCharCount === Detector::SAME_CHARS_ON_SEQ) {
                    return true;
                }
                return Detector::checkAntiDiagonal($bases, $rowNum+1, $colNum-1, $sameCharCount);
            }
        }
        return false;
    }
}