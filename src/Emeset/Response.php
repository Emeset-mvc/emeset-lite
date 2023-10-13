<?php

/**
 * Exemple per a M07.
 *
 * @author: Dani Prados dprados@cendrassos.net
 *
 * Objecte que encapsula la resposta.
 **/

namespace Emeset;

/**
 * Response: Objecte que encapsula la resposta.
 *
 * @author: Dani Prados dprados@cendrassos.net
 *
 * Per guarda tota la informació de la resposta.
 **/
class Response
{

    public $values = [];
    public $header = false;
    public $path;
    public $template;
    public $redirect = false;
    public $json = false;

    /**
     * __construct:  Té tota la informació per crear la resposta
     *
     * @param $path string path fins a la carpeta de plantilles.
     **/
    public function __construct($path = "../src/views/")
    {
        $this->path = $path;
    }

    /**
     * set:  obté un valor de l'entrada especificada amb el filtre indicat
     *
     * @param $id    string identificadro del valor que deem.
     * @param $value mixed valor a desar
     **/
    public function set($id, $value)
    {
        $this->values[$id] = $value;
    }

    /**
     * setSession guarda un valor a la sessió
     *
     * @param  string $id    clau del valor que volem desar
     * @param  mixed  $valor variable que volem desar
     * @return void
     */
    public function setSession($id, $value)
    {
           $_SESSION[$id] = $value;
    }
    
    /**
     * setCookie funció afegida per consistència crea una cookie.
     *
     * Accepta exament els mateixos paràmetres que la funció setcookie de php.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  integer $expire
     * @param  string  $path
     * @param  string  $domain
     * @param  boolean $secure
     * @param  boolean $httponly
     * @return void
     */
    public function setCookie($name, $value = "", $expire = 0, $path = "", $domain = "", $secure = false, $httponly = false)
    {
        setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
    }

    /**
     * setHeader Afegeix una capçalera http a la resposta
     *
     * @param  string $header capçalera http
     * @return void
     */
    public function setHeader($header)
    {
        $this->header = $header;
    }

    /**
     * redirect.  Defineix la resposta com una redirecció. (accepta els mateixos paràmetres que header)
     *
     * @param  string $header capçalera http amb la
     *                        redirecció
     * @return void
     */
    public function redirect($header)
    {
        $this->setHeader($header);
        $this->redirect = true;
    }

    /**
     * setTemplate defineix quina plantilla utilitzarem per la resposta.
     *
     * @param  string $p nom de la plantilla
     * @return void
     */
    public function setTemplate($p)
    {
        $this->template = $p;
    }

    /**
     * setJson força que la resposta sigui en format json.
     *
     * @return void
     */
    public function setJSON()
    {
        $this->json = true;
    }

    /**
     * Genera la resposta HTTP
     *
     * @return void
     */
    public function response()
    {
        if ($this->redirect) {
            header($this->header);
        } elseif ($this->json) {
            echo json_encode($this->values);
        } else {
            if ($this->header !== false) {
                header($this->header);
            }
            extract($this->values);
            include $this->path . $this->template;
        }
    }
}
