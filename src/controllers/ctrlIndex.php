<?php

function ctrlIndex(\Emeset\Request $request, \Emeset\Response $response, \Emeset\Container $container): \Emeset\Response{

    $name = $request->get(INPUT_GET, "name");

    $response->set("name", $name);

    $response->setTemplate("index.php");

    return $response;
    
}
