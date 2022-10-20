# Emeset lite

## El framework per estudiants de 2n DAW.

Versió lite del "Framework" Emeset. 

L'objectiu d'Emeset és introduir el patró MVC (Model- Vista-Controlador) utilitzant funcionalitats bàsiques del llenguatge PHP.

La majoria de frameworks moderns, implementen moltes funcionalitats "entre bastidors", això és útil per a programadors experimentats, ja que els permet centrar-se a desenvolupar funcionalitats més avançades sense haver de pensar en l'arquitectura i altres detalls. Però aquestes facilitats no ajuden en el procés d'aprenentatge.

La versió lite, de fet no és Framework, només ens facilita les classes Contenidor, Petició i Resposta.

Emeset està concebut amb finalitats educatives, no és recomanable utilitzar-lo per aplicacions en producció.

## Controladors

Per simplificar, els controladors són funcions que reben d'entrada tota la informació de la petició i retornen una resposta. El controlador interactua amb els models per gestionar la informació i utilitzen aquesta informació per crear una resposta, què es l'objecte que acaben retornant.

Amb aquestes tres classes ens permet uniformitzar els controladors, ara tots els controladors tenen tres paràmetres d'entrada, els objectes petició, resposta i contenidor i tots els controladors retornen l'objecte resposta després de modificar-lo.

Així els controladors reben tota la informació de la petició HTTP encapsulada en l'objecte petició, tracten aquesta informació, accedeixen a la informació que els cal utilitzant els diferents models i escriuen la informació de sortida a l'objecte resposta.

Els controladors no han d'accedir directament a la informació, d'això s'encarreguen els models, ni han de generar cap sortida, d'això s'encarrega la resposta. La seva responsabilitat és crear una resposta en funció de la informació d'entrada utilitzant els models per accedir a les dades de l'aplicació.

```php
function ctrlIndex($peticio, $resposta, $contenidor){

    $resposta->setTemplate("index.php");

    return $resposta;
    
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

// obtindrà el paràmetre r de la petició GET i escaparà els caràcters especials.
$r = $peticio->get(INPUT_GET, "r");  

// obtindrà el paràmetre r de la petició POST i escaparà els caràcters especials.
$r = $peticio->get(INPUT_POST, "r"); 

// obtindrà el paràmetre r de la petició GET.
$r = $peticio->getRaw(INPUT_COOKIES, "r");  

// obtindrà el paràmetre r de la sessió i escaparà els caràcters especials.
$r = $peticio->get("SESSION", "r"); 

// obtindrà el paràmetre r de la sessió i escaparà els caràcters especials.
$r = $peticio->get("FILES", "r"); 

// obtindrà el paràmetre r de la sessió i escaparà els caràcters especials.
$r = $peticio->get("INPUT_REQUEST", "r"); 


//Si no volem escapar els caràcters especials podem utilitzar el mètode getRaw();
$r = $peticio->getRaw(INPUT_GET, "r");  // obtindrà el paràmetre r de la petició GET.
```

## La resposta

La resposta encapsula la resposta HTTP,  això inclou les cookies, redireccions, capçaleres i variables de sessió (encara que no formin part realment de la resposta HTTP).

```php
// Quan instanciem la classe resposta podem definir en quina carpeta 
// estan les plantilles, per defecte busca a ../src/views/
$resposta = new \Emeset\Resposta("../src/vistes");
```

El mètode set ens permet injectar informació a la vista i el mètode setTemplate ens permet definir quina plantilla volem utilitzar per la vista.

### Plantilles

```php
$resposta->set("nom", $nom);
$resposta->setTemplate("fitxa.php");
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
$resposta->setHeader("HTTP/1.1 404 Not Found");
```

### Redireccions

La resposta en alguns casos pot ser una redirecció. Així podem indicar al navegador que carregui una altra pàgina.

```php
$resposta->redirect("location: index.php?r=login");
```

### Sessió

La resposta ens permet desar informació a la sessió. El PHP ens permet fer-ho directament, amb el Framework Emeset esta encapsulat a l'objecte resposta per unificar l'accés a la informació i així reforçar el concepte que un controlador rep informació d'entrada (la petició) i retorna la informació amb la resposta.


```php
// Quedarà desat a la sessió i podrem consultar en les pròximes consultes.
$resposta->setSession("error", "Missatge d'error");  
```

### Cookies

El métode setCookie()  mapeja la petició a la funcio [setcookie](https://www.php.net/manual/es/function.setcookie.php) de PHP amb els mateixos paràmetres.


```php
public function setCookie($name, $value = "", $expire = 0, $path = "", $domain = "", $secure = false, $httponly = false)
```

Per exemple:
```php
$resposta->setCookie("contador", $contador);
```

### Resposta en format JSON

Si volem generar una resposta en format JSON podem utilitzar el mètode setJson() així  la resposta codificarà a format JSON tota la informació que hem afegit.

```php
// Quedarà desat a la sessió i podrem consultar en les pròximes consultes.
$resposta->setJson();  
```

## El contenidor

El contenidor és el responsable d'instanciar els diferents objectes del projecte. Centranlitzant la responsabilitat de creació de nous objectes ens simplifica el podem canviar d'implementació d'algun objecte, sempre que respecti la signatura (mètodes i paràmetres). Podem assegurar aquesta compatibilitat utilitzant interfaces.

El constructor de la classe contenidor espera l'array de configuració com a paràmetre.
```php
$contenidor = new \Emeset\Contenidor($config);
```

Podem definir un mètode per cada classe que volguem utilitzar i així aquest mètode serà el responsable de la seva instancició.

```php
    public function resposta()
    {
        return new \Emeset\Resposta();
    }
```

## Les vistes

Les vistes són fitxers PHP planers, l'objecte resposta s'encarrega de que en el àmbit del fitxer hi estiguin disponible tota les variables que haguem definit al controlador.

Al ser fitxers PHP podem utilitzat qualsevol funcionalitat de PHP, però és important que les plantilles només tinguin codi relacionat amb la lògica de presentació.

Alhora de definir les Urls dels diferents recursos (imatges, fulls d'estils, fitxers javascript) hem de tenir present que la vista es visualitzarà des de la carpeta public que de fer és la única carpeta accessible publicament. Per tant els path s'han d'ajustar a partir d'aquest punt.

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

function auth($peticio, $resposta, $contenidor, $next){
    /* Aqui aniria el codi per validar si l'usuari està identificat correctament. */

    if($loginOK) {
        // Com compleix les condicions  executem el controlador.
        $resposta = $next($peticio, $resposta, $contenidor);  
    } else {
        // Com no les compleix, aquí redirigim a la ruta definida per aquest cas.  
        $resposta->redirect("Location: index.php?r=login");
    }

    return $resposta;
}

```

