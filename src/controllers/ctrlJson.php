<?php

function ctrlJson($request, $response, $container){

    $response->set("dades", ["name" => "John", "surname" => "Doe"]);    
    $response->setJson();

    return $response;
    
}