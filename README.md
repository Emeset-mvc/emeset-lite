# Emeset lite

##El framework per estudiants de 2n DAW.

Versió lite del "Framework" Emeset. 

L'objectiu d'Emeset és introduir el patró MVC (Model- Vista-Controlador) utilitzant funcionalitats bàsiques del llenguatge PHP.

La majoria de frameworks moderns, implementen moltes funcionalitats "entre bastidors", això és útil per a programadors experimentats, ja que els permet centrar-se a desenvolupar funcionalitats més avançades sense haver de pensar en l'arquitectura i altres detalls. Però aquestes facilitats no ajuden en el procés d'aprenentatge.

La versió lite, de fet no és Framework, només ens facilita les classes Contenidor, Petició i Resposta.

## Controladors

Per simplificar, els controladors són funcions que reben d'entrada tota la informació de la petició i retornen una resposta. El controlador interactua amb els models per gestionar la informació i utilitzen aquesta informació per crear una resposta, què es l'objecte que acaben retornant.

Amb aquestes tres classes ens permet uniformitzar els controladors, ara tots els controladors tenen tres paràmetres d'entrada, els objectes petició, resposta i contenidor i tots els controladors retornen l'objecte resposta després de modificar-lo.

Així els controladors reben tota la informació de la petició HTTP encapsulada en l'objecte petició, tracten aquesta informació, accedeixen a la informació que els cal utilitzant els diferents models i escriuen la informació de sortida a l'objecte resposta.

```php
function ctrlIndex($peticio, $resposta, $contenidor){

    $resposta->setTemplate("index.php");

    return $resposta;
    
} 
```

## Front controller

Amb aquesta versió el FrontController el definim directament a l'index.php amb una sèrie d'ifs.


```php
 $r = '';
 if(isset($_REQUEST["r"])){
    $r = $_REQUEST["r"];
 }
 
 /* Front Controller, aquí es decideix quina acció s'executa */
 if($r == "") {
     $resposta = ctrlIndex($request, $resposta, $contenidor);
 } else {
     echo "No existeix la ruta";
 }
 ```

I al final del fitxer "executem" la resposta,  la resposta pot ser una plantilla a on "injectem" la informació o una redirecció.


```php
 $resposta->resposta();

```





## La petició

L'objecte petició encapsula tota la petició HTTP.

```php

$r = $peticio->get(INPUT_GET, "r");  // obtindrà el paràmetre r de la petició GET i escaparà els caràcters especials.

$r = $peticio->get(INPUT_POST, "r"); // obtindrà el paràmetre r de la petició POST i escaparà els caràcters especials.

$r = $peticio->getRaw(INPUT_COOKIES, "r");  // obtindrà el paràmetre r de la petició GET.

$r = $peticio->get("SESSION", "r"); // obtindrà el paràmetre r de la sessió i escaparà els caràcters especials.

$r = $peticio->get("FILES", "r"); // obtindrà el paràmetre r de la sessió i escaparà els caràcters especials.



$r = $peticio->getRaw(INPUT_GET, "r");  // obtindrà el paràmetre r de la petició GET.

## La resposta


## El contenidor


## Les vistes

Les vistes són fitxers PHP planers, l'objecte resposta s'encarrega de que en el àmbit del fitxer hi estiguin disponible tota les variables que haguem definit al controlador.



## Middleware

El middleware és una capa de codi que s'executa abans o després del controlador,  i pot fer modificacions als objectes petició o resposta. Ens permet afegir la mateixa "capa" a més d'un controlador sense la necessitat de repetir codi, ni de que el controlador hagi de tenir codi que no estigui relacionat directament amb la seva responsavilitat.

Dos exemples habituals de middleware és el control d'accés, on el middleware comprova si l'usuari que ha fet la petició realment té permissos suficients per executar aquell controlador o middleware que afegeix informació comuna a tota l'aplicació a la resposta,  com ara la informació per generar el menú de navegació.


```php

function auth($peticio, $resposta, $contenidor, $next){
    /* Aqui aniria el codi per validar si l'usuari està identificat correctament. */

    if($loginOK) {
        $resposta = $next($peticio, $resposta, $contenidor);  // Com compleix les condicions  executem el controlador.
    } else {
        $resposta->redirect("Location: index.php?r=login");   // Com no les compleix, aquí redirigim a la ruta definida per aquest cas.  
    }

    return $resposta;
}

```