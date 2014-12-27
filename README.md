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

##License

(MIT License)

Copyright (c) 2014 Jonathan Lindber me@jonathanlindberg.com

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
