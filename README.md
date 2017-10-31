# ONTRAPORT PHP library

## Requirements
PHP version 5.3.2 or greater.

## Documentation
Visit our [API documentation](https://api.ontraport.com/doc) page for detailed usage information and code examples.

## Composer
If you are using composer, you can install our SDK on the command line by entering
```
composer require ontraport/sdk-php
```
You can also manually add it to your `composer.json` file:
```
{
    "require": {
        "ontraport/sdk-php": "*"
    }
}
```
To use the library, include Composer's autoload in your scripts:
```
require_once('vendor/autoload.php');
```
## Manual Installation
If you are not using composer, you can download the latest version. Make sure to include the Ontraport.php file to use this library in your code:
```
require_once('path/to/src/Ontraport.php');
```
## Namespacing
Our API wrapper is namespaced. 
This helps you to avoid collisions between the classes and functions in our wrapper and external classes and functions which might have the same name.
This means if you want to create an instance of the Ontraport class, you need to either import the OntraportAPI namespace:
```
use OntraportAPI\Ontraport;
```
or use a qualified name:
```
$client = new OntraportAPI\Ontraport("2_AppID_12345678","Key5678")
```
[Click here](http://php.net/manual/en/language.namespaces.php) for more about namespacing in PHP.
