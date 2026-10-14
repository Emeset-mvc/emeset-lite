<?php

/**
 * Example function - Exemple d'estructura d'una funció middleware.
 *
 * @param \Emeset\Request $request
 * @param \Emeset\Response $response
 * @param \Emeset\Container $container
 * @param callable(\Emeset\Request, \Emeset\Response, \Emeset\Container): \Emeset\Response $next
 * @return \Emeset\Response
 */
function example(\Emeset\Request $request, \Emeset\Response $response, \Emeset\Container $container, callable $next): \Emeset\Response{

    // Aquí va el codi del middleware
    $response = $next($request, $response, $container);

    return $response;
    
}
