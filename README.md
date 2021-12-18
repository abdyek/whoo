# Whoo
Whoo is a database agnostic authentication library to manage authentication operation easily. Whoo provides you a layer to access and manage user and authentication in your application using [Propel ORM](https://github.com/propelorm/Propel2).

## Installation
`composer require propel/propel "2.0.0-beta1"`

`composer require abdyek/whoo "1.0.0-beta5"`

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
use Abdyek\Whoo\Whoo;
use Abdyek\Whoo\Tool\JWT;

// Whoo once needs to load config
Abdyek\Whoo\Tool\Config::load();

// set secret key of JWT
JWT::setSecretKey('top_secret');
 
// Sign Up
$signUp = new Whoo('SignUp', [
    'email'=>'new_user_email@example.com',
    'password'=>'this_is_password'
]);

$signUp->catchException('NotUniqueEmail', function() {
    // Oops! the email already is registered
});

$signUp->run();

if($signUp->success) {
    echo 'signed up successfully!';
}

// Sign In

$signIn = new Whoo('SignIn', [
    'email'=>'new_user_email@example.com',
    'password'=>'this_is_password'
]);

$signIn->catchException('IncorrectPassword', function() {
    // Oops! Wrong password
})->catchException('TwoFactorAuthEnabled', function() use($signIn) {
    // You can send authentication code to user via any way you want
    // $signIn->getException()->authCode;
})->run();

if($signIn->success) {
    echo 'signed in successfully!';
}

// JWT Payload

use Abdyek\Whoo\Exception\InvalidTokenException;

try {
    // $jwt is incoming token from the user
    $payload = JWT::getPayload($jwt);
    $userId = $payload->whoo->userId;
    // userId is a unique number for each user in Whoo user model.
    // You can use it in your application to expand the user model
} catch(InvalidTokenException $e) {
    // Oops!! The token invalid!!   
}
```

## Documentation
You can reach all controller class ant other information at [wiki](https://github.com/abdyek/whoo/wiki) page. You can feedback me about documentation.

## Versioning
Whoo will have semantic versioning when first stable release.
