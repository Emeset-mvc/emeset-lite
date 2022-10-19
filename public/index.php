<?php

/**
 * Aquest fitxer és un exemple de Front Controller, pel qual passen totes les peticions.
 */

 include "../src/config.php";
 include "../src/controllers/ctrlIndex.php";
 include "../src/controllers/ctrlJson.php";

/**
  * Carreguem les classes del Framework Emeset
*/
  
 include "../src/Emeset/Contenidor.php";
 include "../src/Emeset/Peticio.php";
 include "../src/Emeset/Resposta.php";

 $request = new \Emeset\Peticio();
 $resposta = new \Emeset\Resposta();
 $contenidor = new \Emeset\Contenidor($config);

 /* 
  * Aquesta és la part que fa que funcioni el Front Controller.
  * Si no hi ha cap paràmetre, carreguem la pàgina d'inici.
  * Si hi ha paràmetre, carreguem la pàgina que correspongui.
  * Si no existeix la pàgina, carreguem la pàgina d'error.
  */
 $r = '';
 if(isset($_REQUEST["r"])){
    $r = $_REQUEST["r"];
 }
 
 /* Front Controller, aquí es decideix quina acció s'executa */
 if($r == "") {
     $resposta = ctrlIndex($request, $resposta, $contenidor);
 } elseif($r == "json") {
  $resposta = ctrlJson($request, $resposta, $contenidor);
} else {
     echo "No existeix la ruta";
 }

 /* Enviem la resposta al client. */
 $resposta->resposta();