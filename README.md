davamigo/simple-proxy
=====================

A simple PHP anonymous proxy server which acts as intermediary for HTTP requests to get resources from a external server hiding the client request.

Install the project
-------------------

* Clone the project.
```bash
$ git clone git@github.com:davamigo/simple-proxy.git MyTargetFolder
$ cd MyTargetFolder
```

* Run [**composer**](https://getcomposer.org/) globally:
```bash
$ composer install
```

* ...or locally:
```bash
$ php -r "readfile('https://getcomposer.org/installer');" | php
$ php composer.phar install
```

To run the tests
----------------

* To run [**phpunit**](https://phpunit.de/):
```
$ bin/phpunit
```

* To run **phpunit** with coverage report ([**XDebug**](https://xdebug.org/) needed):
```
$ bin/phpunit --coverage-html ./runtime/coverage
```
