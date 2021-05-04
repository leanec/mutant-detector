<?php

namespace  App\Middleware;

class Validator
{
    /**
     * Validad que el DNA tenga el formato solicitado
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        $input = $request->getParsedBody();
        if (empty($input['dna'])) {
            $response->getBody()->write('DNA is empty');
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        }
    
        $dna = $input['dna'];

        if (!is_array($dna)) {
            $response->getBody()->write('DNA must be an array');
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        }

        $dnaSize = sizeof($dna);

        foreach ($dna as $fila) {
            if ($dnaSize !== strlen($fila)) {
                $response->getBody()->write('DNA must be an array NxN');
                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(400);
            }

            if (preg_match('/[^ATCG]/', $fila)) {
                $response->getBody()->write('DNA must contain only A,T,C,G');
                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(400);
            }
        }

        $response = $next($request, $response);

        return $response;
    }
}
