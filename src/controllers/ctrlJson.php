<?php

function ctrlJson($peticio, $resposta, $contenidor){

    $resposta->set("dades", ["name" => "John", "surname" => "Doe"]);    
    $resposta->setJson();

    return $resposta;
    
}