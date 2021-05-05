<?php

declare(strict_types = 1);

namespace App\Handlers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ApiError extends \Slim\Handlers\Error
{
    public function __invoke(Request $request, Response $response, \Exception $exception): Response 
    {
        $data = [
            'message' => $exception->getMessage(),
            'status' => 'error',
            'code' => 500
        ];
        $body = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        $response->getBody()->write((string) $body);

        return $response
            ->withStatus(500)
            ->withHeader('Content-type', 'application/json');
    }
}