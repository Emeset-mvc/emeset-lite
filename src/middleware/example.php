<?php

/**
 * Example function - Exemple d'estructura d'una funció middleware.
 *
 * @param \Emeset\Request $request
 * @param \Emeset\Response $response
 * @param \Emeset\Container $container
 * @param callable $next
 * @return \Emeset\Response
 */
function example($request, $response, $container, $next){

    // Aquí va el codi del middleware
    $response = $next($request, $response, $container);

    return $response;
    
}