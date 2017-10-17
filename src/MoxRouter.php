<?php
/*
 * Created by Jonathan Lindberg. me@jonathanlindberg.com
 */
namespace Moxxie;

class MoxRouter {

    private $routes = Array();

    private $hooks = Array('before_route' => Array(), 'after_route' => Array());

    private $notFound;

    public $baseUri = "/";

    public function __call($method, $arguments) {
        return call_user_func_array(Closure::bind($this->$method, $this, get_called_class()), $arguments);
    }

    private function add($method, $route, $function, $class = false){
        if(empty($route)){
            throw new Exception('The route can not be empty');
        }
        $this->routes[] = [
            'path' => $this->baseUri . ltrim($route, "/"),
            'function' => $function, 'class' => $class,
            'method' => $method
        ];
    }

    public function get($route, $function, $class = false){
        $this->add('GET', $route, $function, $class);
    }
    public function post($route, $function, $class = false){
        $this->add('POST', $route, $function, $class);
    }
    public function put($route, $function, $class = false){
        $this->add('PUT', $route, $function, $class);
    }
    public function delete($route, $function, $class = false){
        $this->add('DELETE', $route, $function, $class);
    }
    public function patch($route, $function, $class = false){
        $this->add('PATCH', $route, $function, $class);
    }

    public function before($function){
        $this->hooks['before_route'][] = $function;
    }

    public function after($function){
        $this->hooks['after_route'][] = $function;
    }

    public function run(){
        $uri = $_SERVER['REQUEST_URI'];
        if(strpos($uri, "?") !== -1) $uri = substr($uri, 0, (strpos($uri, "?")));
        rtrim($uri, "/");
        $uri .= "/";

        $found = false;
        foreach($this->routes as $route){
            $path = $route['path'];

            rtrim($path, "/");
            $path .= "/";

            $string = str_replace("/", "\/", $path);
            $pattern = "/(\{)(.*?)(\})/";
            $replacementKeys = "\{(.*)\}";
            $replacementValues = "([A-z0-9]+)";

            preg_match("/".preg_replace($pattern, $replacementKeys, $string)."/", $path, $keys);
            preg_match("/".preg_replace($pattern, $replacementValues, $string)."/", $uri, $values);

            if(is_array($values) && isset($values[0]) && $values[0] == $uri && is_array($keys) && isset($keys[0]) && $keys[0] == $path && $_SERVER['REQUEST_METHOD'] == $route['method']){
                $found = true;
                unset($values[0]);
                foreach($this->hooks['before_route'] as $hook){
                    call_user_func($hook);
                }
                if($route['class'] !== false){
                    call_user_func_array(Array($route['class'], $route['function']), $values);
                }else{
                    call_user_func_array($route['function'], $values);
                }

                foreach($this->hooks['after_route'] as $hook){
                    call_user_func($hook);
                }
                break;
            }
        }
        if(!$found){
            if(is_callable($this->notFound)){
                die(call_user_func($this->notFound));
            }
            die("<h2>404</h2>Sorry, but the page you are looking for is not there.");
        }
    }

    public function notFound($function){
        $this->notFound = $function;
    }

    public function autoLoader($directory){
        spl_autoload_register(function ($className) use($directory) {
            if(is_file($directory .'/'. $className . '.php')){
                include $directory .'/' . $className . '.php';
            }
        });
    }
}
