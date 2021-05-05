<?php

declare(strict_types = 1);

namespace App\Handlers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class NotFound extends \Slim\Handlers\NotFound
{
    public function __invoke(Request $request, Response $response): Response 
    {
        $data = [
            'message' => 'Endpoint not found',
            'status' => 'error',
            'code' => 404,
        ];
        $body = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        $response->getBody()->write((string) $body);

        return $response
            ->withStatus(404)
            ->withHeader('Content-type', 'application/json');
    }
}