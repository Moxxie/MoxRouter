MoxRouter
=========

A super simple PHP router

## Usage
```php
<?php
require('MoxRouter.php');

$router = new MoxRouter();

// Index route
$router->add('/', function(){
  echo "Hello World!";
});

// Passing parameters
$router->add('/hello/{name}', function($name){
  echo "Hello " . $name;
});

//Routing to a specific function
$router->add('/function/{name}', 'myFunction');

function myFunction($name){
  echo "Hello " . $name;
}

//Routing to a function in a class
$router->add('/class/function/{name}', 'myFunction', 'myClass');

class myClass{
  function myFunction($name){
    echo "Hello " . $name;
  }
}

//Hooks
//You can have as many hooks as you want.
$route->before(function(){
  echo "This will execute before the router";
});

$route->after(function(){
  echo "This will execute after the router";
});

//You can ovveride the default 404 handler
$route->notFound(function(){
  echo "Woopsie, the page you are looking for is missing...";
});

//Simple DI usage
$route->someClass = new SomeClass();
$route->add('/di', function() use($route){
  echo $route->someClass->someMethod();
});

// Run the router
$router->run();
```
