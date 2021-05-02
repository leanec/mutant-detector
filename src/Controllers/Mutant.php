<?php

namespace App\Controllers;

use App\Utils\Detector;

class Mutant {
    
    /**
     * Verifica si el DNA es mutante o no
     * 
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function isMutant($request, $response)
    {
        $input = $request->getParsedBody();
        $mutant = Detector::checkDna($input['dna']);
        if ($mutant) {
            return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(200);
        } else {
            return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(403);
        }
    }
}