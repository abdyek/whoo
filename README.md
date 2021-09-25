# Whoo
Whoo is a database agnostic authentication library to manage authentication operation easily. Whoo provides you a layer to access and manage user and authentication in your application using Propel ORM. Actually whoo only presents [JWT](https://github.com/firebase/php-jwt) library and [Propel ORM](ttps://github.com/propelorm/Propel2) as wrapped to import authentication operation in your application fast.
## Installation
`composer require abdyek/whoo "1.0.0-beta3"`

### Notice!

Whoo uses version 2.0 of Propel. Version 2 is not stable version of Propel. So you must hold `"minimum-stability":"beta"` in your composer.json until I replace the stable version of Propel instead of the beta version.

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
use Abdyek\Whoo\Controller\SignUp;
use Abdyek\Whoo\Controller\SignIn;
use Abdyek\Whoo\Exception\NotUniqueEmailException;
use Abdyek\Whoo\Exception\IncorrectPasswordException;
use Abdyek\Whoo\Exception\TwoFactorAuthEnabledException;
use Abdyek\Whoo\Exception\InvalidTokenException;
use Abdyek\Whoo\Tool\JWT;
 
// Sign Up
try {
    new SignUp([
        'email'=>'new_user_email@example.com',
        'password'=>'this_is_password'
    ]);
    // successfully registered
} catch(NotUniqueEmailException $e) {
    // Oops! the email already is registered
}

// Sign In
try {
    $signIn = new SignIn([
        'email'=>'new_user_email@example.com',
        'password'=>'this_is_password'
    ]);
    // You can use $signIn->$jwt you want
} catch(IncorrectPasswordException $e) {
    // Wrong password!
} catch(TwoFactorAuthEnabledException $e) {
    // You can send authentication code to user via any way you want
    // $e->authenticationCode;
}

// JWT Payload
try {
    $payload = JWT::getPayload($jwt);
    $userId = $payload['userId'];
    // userId is a unique number for every user in Whoo user model.
    // You can use it in your application to expand the user model
} catch(InvalidTokenException $e) {
    // Oops!! The token invalid!!   
}
```



## Documentation
You can reach all controller class ant other information at [wiki](https://github.com/abdyek/whoo/wiki) page. You can feedback me about documentation.
