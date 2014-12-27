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

// Run the router
$router->run();
```
