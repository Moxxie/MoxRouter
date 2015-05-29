<?php
/*
 * Created by Jonathan Lindberg. me@jonathanlindberg.com
 */
class MoxRouter {

    private $routes = Array();

    private $hooks = Array('before_route' => Array(), 'after_route' => Array());

    private $notFound;

    public function __call($method, $arguments) {
        return call_user_func_array(Closure::bind($this->$method, $this, get_called_class()), $arguments);
    }

    public function add($route, $function, $class = false){
        if(empty($route)){
            throw new Exception('The route can not be empty');
        }
        $this->routes[] = Array('path' => $route, 'function' => $function, 'class' => $class);
    }

    public function before($function){
        $this->hooks['before_route'][] = $function;
    }

    public function after($function){
        $this->hooks['after_route'][] = $function;
    }

    public function run(){
        $uri = $_SERVER['REQUEST_URI'];

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
            $replacementValues = "([A-z]+)";

            preg_match("/".preg_replace($pattern, $replacementKeys, $string)."/", $path, $keys);
            preg_match("/".preg_replace($pattern, $replacementValues, $string)."/", $uri, $values);

            if(is_array($values) && isset($values[0]) && $values[0] == $uri && is_array($keys) && isset($keys[0]) && $keys[0] == $path){
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
