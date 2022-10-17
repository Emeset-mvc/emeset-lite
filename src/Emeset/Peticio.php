<?php

/**
 * Exemple per a M07.
 *
 * @author: Dani Prados dprados@cendrassos.net
 *
 * Classe gestiona la petició HTTP.
 **/

namespace Emeset;

/**
 * Peticio: Classe gestiona la petició HTTP.
 *
 * @author: Dani Prados dprados@cendrassos.net
 *
 * Encapsula la petició HTTP per permetre llegir-la com una entrada.
 **/
class Peticio
{

    /**
     * __construct:  Crear el petició http
     **/
    public function __construct()
    {
        session_start();
    }

    /**
     * get:  obté un valor de l'entrada especificada amb el filtre indicat
     *
     * @param $input   string identificador de l'entrada.
     * @param $id      string amb la tasca.
     * @param $filtre  int filtre a aplicar
     * @param $opcions int opcions del filtre si volem un array FILTER_REQUIRE_ARRAY
     **/
    public function get($input, $id, $filtre = FILTER_SANITIZE_STRING, $opcions = 0)
    {
        $result = false;
        if ($input === 'SESSION') {
            $result = $_SESSION[$id];
        } elseif ($input === 'FILES') {
            $result = $_FILES[$id];
        } else {
            $result = filter_input($input, $id, $filtre, $opcions);
        }
        return $result;
    }

    /**
     * getRaw:  obté un valor de l'entrada especificada sense filtrar
     *
     * @param $input   string identificador de l'entrada.
     * @param $id      string amb la tasca.
     * @param $filtre  int filtre a aplicar
     * @param $opcions int opcions del filtre si volem un array FILTER_REQUIRE_ARRAY
     **/
    public function getRaw($input, $id, $opcions = 0)
    {
        return $this->get($input, $id, FILTER_DEFAULT, $opcions);
    }
}
