<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\Services\RecordService;
use App\Utils\Detector;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

class RecordController {
    
    protected Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    protected function getRecordService() : RecordService
    {
        return $this->container->get('record_service');
    }

    /**
     * Verifica si el DNA es mutante o no
     * 
     * @param  Request      $request  PSR7 request
     * @param  Response     $response PSR7 response
     *
     * @return Response
     */
    public function isMutant(Request $request, Response $response) : Response
    {
        $input = $request->getParsedBody();
        $dna = $input['dna'];
        
        $record = $this->getRecordService()->getOne($dna);
        if (is_null($record)) {
            $record = $this->getRecordService()->create($dna, Detector::checkDna($dna));
        }

        if ($record->mutant) {
            return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(200);
        } else {
            return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(403);
        }
    }

    /**
     * Obtiene estadisticas de los ADN analizados
     * 
     * @param  Request      $request  PSR7 request
     * @param  Response     $response PSR7 response
     *
     * @return Response
     */
    public function stats(Request $request, Response $response) : Response
    {
        $input = $request->getParsedBody();
        
        $records = $this->getRecordService()->getStats();

        return $response->withJson($records, 200);
    }
}