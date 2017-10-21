MoxRouter
=========

A super simple and fast PHP router

## Installation

It's recommended that you use [Composer](https://getcomposer.org/) to install MoxRouter.

```bash
$ composer require moxxie/moxrouter "^0.2.0"
```
## Usage

Create an index.php file with the following contents:

```php
<?php
require 'vendor/autoload.php';
 
$router = new Moxxie\MoxRouter();
 
$router->get('/{message}', function($message){
  echo "Hello " . $message . "!";
});
 
$router->run();
```

You can test this using the built-in server that comes with PHP:
```bash
$ php -S localhost:8000
```

http://localhost:8000/world will now display "Hello, world!".

## License

(MIT License)

Copyright (c) 2017 Moxxie - https://github.com/Moxxie/MoxRouter

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
