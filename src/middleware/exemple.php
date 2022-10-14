<?php

/**
 * Exemple function - Exemple d'estructura d'una funció middleware.
 *
 * @param \Emeset\Peticio $peticio
 * @param \Emeset\Resposta $resposta
 * @param \Emeset\Contenidor $contenidor
 * @param Callable $next
 * @return \Emeset\Resposta
 */
function exemple($peticio, $resposta, $contenidor, $next){

    // Aquí va el codi del middleware
    $resposta = $next($peticio, $resposta, $contenidor);

    return $resposta;
    
}