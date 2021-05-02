<?php
require '../vendor/autoload.php';

use App\Controllers\Mutant;

echo "<h1>Demo de MutantDetector</h1>";

echo "<table><tbody><tr><td>A </td><td>T </td><td>G </td><td>C </td><td>G </td><td>A</td></tr><tr><td>C </td><td>A </td><td>G </td><td>T </td><td>G </td><td>C</td></tr><tr><td>T </td><td>T </td><td>A </td><td>T </td><td>T </td><td>T</td></tr><tr><td>A </td><td>G </td><td>A </td><td>C </td><td>G </td><td>G</td></tr><tr><td>G </td><td>C </td><td>G </td><td>T </td><td>C </td><td>A</td></tr><tr><td>T </td><td>C </td><td>A </td><td>C </td><td>T </td><td>G</td></tr></tbody></table>";
echo Mutant::isMutant(["ATGCGA","CAGTGC","TTATTT","AGACGG","GCGTCA","TCACTG"]) ? '<b>¡Es Mutante!</b>' : 'No es mutante';
echo "<br><br>";
echo "<table><tbody><tr><td>A </td><td>T </td><td>G </td><td>C </td><td>G </td><td>A</td></tr><tr><td>C </td><td>A </td><td>G </td><td>T </td><td>G </td><td>C</td></tr><tr><td>T </td><td>T </td><td>A </td><td>T </td><td>G </td><td>T</td></tr><tr><td>A </td><td>G </td><td>A </td><td>A </td><td>G </td><td>G</td></tr><tr><td>C </td><td>C </td><td>C </td><td>C </td><td>T </td><td>A</td></tr><tr><td>T </td><td>C </td><td>A </td><td>C </td><td>T </td><td>G</td></tr></tbody></table>";
echo Mutant::isMutant(["ATGCGA","CAGTGC","TTATGT","AGAAGG","CCCCTA","TCACTG"]) ? '<b>¡Es Mutante!</b>' : 'No es mutante';
echo "<br><br>";