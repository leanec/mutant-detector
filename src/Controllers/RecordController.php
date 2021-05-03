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
        
        try{
            $record = $this->getRecordService()->getOne($dna);
            $mutant = $record->getMutant();
        } catch (\Exception $e) {
            $mutant = Detector::checkDna($dna);
            $record = $this->getRecordService()->create($dna, $mutant);
        }

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