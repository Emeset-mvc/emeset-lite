<?php

function ctrlJson($request, $response, $container){

    $response->set("data", ["name" => "John", "surname" => "Doe"]);    
    $response->setJson();

    return $response;
    
}