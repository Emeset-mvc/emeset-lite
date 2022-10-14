<?php

function ctrlIndex($peticio, $resposta, $contenidor){

    $resposta->setTemplate("index.php");

    return $resposta;
    
}