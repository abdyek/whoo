# Whoo
Whoo is a database agnostic authentication library to manage authentication operation easily. Whoo provides you a layer to access and manage user and authentication in your application using [Propel ORM](https://github.com/propelorm/Propel2).

## Installation
`composer require propel/propel "2.0.0-beta1" abdyek/whoo ^1.0`

## Features

* Easy installation, building, usage and learning
* Two-Factor Authentication support
* OAuth2 authentication provider support
* Database agnostic. MySQL, MS SQL Server, PostgreSQL, SQLite, Oracle support (powered by Propel ORM)
* Able to configure for you application
* Portable and standalone
* Destroyable JSON Web Tokens

## Getting Started

```php
require 'vendor/autoload.php';

use Abdyek\Whoo\Whoo;

// Whoo once needs to load Propel config
Whoo::loadPropelConfig();

// Sign Up
$signUp = new Whoo('SignUp', [
    'email' => 'new_user_email@example.com',
    'password' => 'this_is_password',
    'username' => 'abdyek',
]);

$signUp->success(function(array $response) {
    echo $response['user']->getUsername() . ' registered whoo database';
})->exception('NotUniqueEmail', function($e) {
    // Oops! the email already is registered
})->exception('NotUniqueUsername', function($e) {
    // Oops! the username already is registered
});

$signUp->run();
```

## Documentation
You can reach all controller class and other info at [wiki](https://github.com/abdyek/whoo/wiki) page.

## Versioning
Whoo will have semantic versioning when first stable release.
