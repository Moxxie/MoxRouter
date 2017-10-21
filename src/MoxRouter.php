<?php
/*
 * Created by Moxxie. https://github.com/Moxxie/MoxRouter
 */
namespace Moxxie;

class MoxRouter {

  private $routes = [];

  private $hooks = ['before_route' => [], 'after_route' => []];

  private $notFound;

  public $baseUri = '/';

  private $container = [];

  public function __call($method, $arguments) {
    if(isset($this->container[$method])){
      return call_user_func_array(\Closure::bind($this->container[$method], $this), $arguments);
    }
    throw new \Exception("Call to undefined method: " . $method);
  }

  public function addContainer($name, $function){
    $this->container[$name] = $function;
  }

  private function add($method, $route, $function){
    if(empty($route)){
      throw new \Exception("The route can not be empty");
    }
    $this->routes[] = [
      'path' => $this->baseUri . ltrim($route, '/'),
      'function' => $function,
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

  public function run($container = false){
    if($container !== false) $this->container = $container;

    $uri = $_SERVER['REQUEST_URI'];

    if(($pos = strpos($uri, "?")) !== false) $uri = substr($uri, 0, $pos);

    $uri = rtrim($uri, '/');

    $found = false;
    foreach($this->routes as $route){
      if($_SERVER['REQUEST_METHOD'] !== $route['method']) continue;

      $path = rtrim($route['path'], '/');
      $string = str_replace('/', "\/", $path);
      $pattern = '/^' . preg_replace("/(\{)(.*?)(\})/", "([A-z0-9\-\_]+)", $string) . '$/';
      $match = preg_match($pattern, $uri, $values);

      if($match === 1){
        $found = true;
        unset($values[0]);
        foreach($this->hooks['before_route'] as $hook){
          call_user_func(\Closure::bind($hook, $this));
        }

        call_user_func_array(\Closure::bind($route['function'], $this), $values);

        foreach($this->hooks['after_route'] as $hook){
          call_user_func(\Closure::bind($hook, $this));
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
}
