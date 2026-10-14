<?php

function ctrlJson(\Emeset\Request $request, \Emeset\Response $response, \Emeset\Container $container): \Emeset\Response{

    $response->set("data", ["name" => "John", "surname" => "Doe"]);    
    $response->setJson();

    return $response;
    
}
