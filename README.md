# Emeset lite

## El framework per estudiants de 2n DAW.

Versió lite del "Framework" Emeset. 

L'objectiu d'Emeset és introduir el patró MVC (Model- Vista-Controlador) utilitzant funcionalitats bàsiques del llenguatge PHP.

La majoria de frameworks moderns, implementen moltes funcionalitats "entre bastidors", això és útil per a programadors experimentats, ja que els permet centrar-se a desenvolupar funcionalitats més avançades sense haver de pensar en l'arquitectura i altres detalls. Però aquestes facilitats no ajuden en el procés d'aprenentatge.

La versió lite, de fet no és Framework, només ens facilita les classes Container, Petició i Response.

Emeset està concebut amb finalitats educatives, no és recomanable utilitzar-lo per aplicacions en producció.

## Controladors

Per simplificar, els controladors són funcions que reben d'entrada tota la informació de la petició i retornen una resposta. El controlador interactua amb els models per gestionar la informació i utilitzen aquesta informació per crear una resposta, què es l'objecte que acaben retornant.

Amb aquestes tres classes ens permet uniformitzar els controladors, ara tots els controladors tenen tres paràmetres d'entrada, els objectes petició, resposta i contenidor i tots els controladors retornen l'objecte resposta després de modificar-lo.

Així els controladors reben tota la informació de la petició HTTP encapsulada en l'objecte petició, tracten aquesta informació, accedeixen a la informació que els cal utilitzant els diferents models i escriuen la informació de sortida a l'objecte resposta.

Els controladors no han d'accedir directament a la informació, d'això s'encarreguen els models, ni han de generar cap sortida, d'això s'encarrega la resposta. La seva responsabilitat és crear una resposta en funció de la informació d'entrada utilitzant els models per accedir a les dades de l'aplicació.

```php
function ctrlIndex($request, $response, $container){

    $response->setTemplate("index.php");

    return $response;
    
} 
```

## Front controller

Amb aquesta versió el FrontController el definim directament a l'index.php amb una sèrie d'ifs.

L'única responsabilitat del FrontController és inicialitzar l'aplicació, decidir quin controlador s'ha d'executar i enviar la resposta del controlador al client.


```php
 $r = '';
 if(isset($_REQUEST["r"])){
    $r = $_REQUEST["r"];
 }
 
 /* Front Controller, aquí es decideix quina acció s'executa */
 if($r == "") {
     $response = ctrlIndex($request, $response, $container);
 } else {
     echo "No existeix la ruta";
 }
 ```

I al final del fitxer "executem" la resposta,  la resposta pot ser una plantilla a on "injectem" la informació o una redirecció.


```php
 $response->response();

```

## La petició

L'objecte petició encapsula tota la petició HTTP.

```php

// obtindrà el paràmetre r de la petició GET i escaparà els caràcters especials.
$r = $request->get(INPUT_GET, "r");  

// obtindrà el paràmetre r de la petició POST i escaparà els caràcters especials.
$r = $request->get(INPUT_POST, "r"); 

// obtindrà el paràmetre r de la petició GET.
$r = $request->getRaw(INPUT_COOKIES, "r");  

// obtindrà el paràmetre r de la sessió i escaparà els caràcters especials.
$r = $request->get("SESSION", "r"); 

// obtindrà el paràmetre r de la sessió i escaparà els caràcters especials.
$r = $request->get("FILES", "r"); 

// obtindrà el paràmetre r de la sessió i escaparà els caràcters especials.
$r = $request->get("INPUT_REQUEST", "r"); 


//Si no volem escapar els caràcters especials podem utilitzar el mètode getRaw();
$r = $request->getRaw(INPUT_GET, "r");  // obtindrà el paràmetre r de la petició GET.
```

## La resposta

La resposta encapsula la resposta HTTP,  això inclou les cookies, redireccions, capçaleres i variables de sessió (encara que no formin part realment de la resposta HTTP).

```php
// Quan instanciem la classe resposta podem definir en quina carpeta 
// estan les plantilles, per defecte busca a ../src/views/
$response = new \Emeset\Response("../src/vistes");
```

El mètode set ens permet injectar informació a la vista i el mètode setTemplate ens permet definir quina plantilla volem utilitzar per la vista.

### Plantilles

```php
$response->set("nom", $nom);
$response->setTemplate("fitxa.php");
```

Les plantilles de les vistes han de ser fitxers PHP, a les vistes només hi ha d'haver codi relacionat amb la visualització, és la seva única responsabilitat.

Amb l'exemple anterior la plantilla podria visualitzar el nom.

```html
<html>
<body>
<?=$nom;?>
</body>
</html>
```

### Capçaleres HTTP

Podem afegir informació a la capçalera de respota HTTP.

```php
$response->setHeader("HTTP/1.1 404 Not Found");
```

### Redireccions

La resposta en alguns casos pot ser una redirecció. Així podem indicar al navegador que carregui una altra pàgina.

```php
$response->redirect("location: index.php?r=login");
```

### Sessió

La resposta ens permet desar informació a la sessió. El PHP ens permet fer-ho directament, amb el Framework Emeset esta encapsulat a l'objecte resposta per unificar l'accés a la informació i així reforçar el concepte que un controlador rep informació d'entrada (la petició) i retorna la informació amb la resposta.


```php
// Quedarà desat a la sessió i podrem consultar en les pròximes consultes.
$response->setSession("error", "Missatge d'error");  
```

### Cookies

El métode setCookie()  mapeja la petició a la funcio [setcookie](https://www.php.net/manual/es/function.setcookie.php) de PHP amb els mateixos paràmetres.


```php
public function setCookie($name, $value = "", $expire = 0, $path = "", $domain = "", $secure = false, $httponly = false)
```

Per exemple:
```php
$response->setCookie("contador", $contador);
```

### Response en format JSON

Si volem generar una resposta en format JSON podem utilitzar el mètode setJson() així  la resposta codificarà a format JSON tota la informació que hem afegit.

```php
// Quedarà desat a la sessió i podrem consultar en les pròximes consultes.
$response->setJson();  
```

## El contenidor

El contenidor és el responsable d'instanciar els diferents objectes del projecte. Centralitzar la responsabilitat de creació de nous objectes ens desacobla els controladors dels objectes que utilitzen, treu la lògica d'inicialització dels controladors i ens simplifica el canvi d'implementació d'alguns objectes, sempre que respecti la signatura (mètodes i paràmetres), podem assegurar aquesta compatibilitat utilitzant interfaces.

El constructor de la classe contenidor espera l'array de configuració com a paràmetre.
```php
$container = new \Emeset\Container($config);
```

Per ampliar el contenidor podem extendre la classe i en el nou contenidor definir un mètode per cada classe que volguem utilitzar i així aquest mètode serà el responsable de la seva instancició.

```php
class Mycontainer extends Emeset\Container {

    public $db = null;

    public function __construct($config){
        parent::__construct($config);
        // Aqui va la logica de connexió a la base de dades
        $this->db = $db; 
    }
    
    public function response()
    {
        return new \Emeset\Response();
    }

    public function user()
    {
        return new user($this->db);
    }

}
   
```
Un cop hem definit la nova versió de contenidor ja la podem utilitzar en la nostra aplicació.

## Les vistes

Les vistes són fitxers PHP planers, l'objecte resposta s'encarrega que en l'àmbit del fitxer hi estiguin disponible totes les variables que haguem definit al controlador.

En ser fitxers PHP podem utilitzar qualsevol funcionalitat de PHP, però és important que les plantilles només tinguin codi relacionat amb la lògica de presentació.

A l'hora de definir les Urls dels diferents recursos (imatges, fulls d'estils, fitxers javascript) hem de tenir present que la vista es visualitzarà des de la carpeta public que de fer és l'única carpeta accessible públicament. Per tant, els path s'han d'ajustar a partir d'aquest punt.


```
└── public
    ├── css
    │   └── web.css
    └── index.php
```

Per enllaçar el full d'estils el path seria *css/web.css*



## Middleware

El middleware és una capa de codi que s'executa abans o després del controlador,  i pot fer modificacions als objectes petició o resposta. Ens permet afegir la mateixa "capa" a més d'un controlador sense la necessitat de repetir codi, ni de que el controlador hagi de tenir codi que no estigui relacionat directament amb la seva responsavilitat.

Dos exemples habituals de middleware és el control d'accés, on el middleware comprova si l'usuari que ha fet la petició realment té permissos suficients per executar aquell controlador o middleware que afegeix informació comuna a tota l'aplicació a la resposta,  com ara la informació per generar el menú de navegació.


```php

function auth($request, $response, $container, $next){
    /* Aqui aniria el codi per validar si l'usuari està identificat correctament. */

    if($loginOK) {
        // Com compleix les condicions  executem el controlador.
        $response = $next($request, $response, $container);  
    } else {
        // Com no les compleix, aquí redirigim a la ruta definida per aquest cas.  
        $response->redirect("Location: index.php?r=login");
    }

    return $response;
}

```

