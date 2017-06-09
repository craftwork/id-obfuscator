# ID Obfuscator (beta)
A simple encoder to obfuscate database IDs.

This is particularly useful when you don't want to expose sensitive information about your application, for instance the number of users or orders.

Imagine having URLs like these:

```
http://example.com/user/5
http://example.com/order/2835
```

then you can convert them into:

```
http://example.com/user/J
http://example.com/order/3l
```

## Note
This is not an encryption library and should not be used for security purposes.

It is not advisable saving encoded numbers but encoding and decoding IDs on the fly.
 
You can use a custom character set and a salt to shuffle the characters.

It is not advisable to change these at run time as some users might share or bookmark links. To ensure users don't end up on broken links use always the same character set and salt. If the links are restricted to logged in user it might be a good idea to use a user specific salt, such as an uuid.

## Installation

```
$ composer install lux-php/id-obfuscator
```

## Requirements

PHP 7.0.

## Usage

```php
$obfuscator = new IdObfuscator();
$obfuscator->encode(10); // k
$obfuscator->encode(1234566); // eTAg

$obfuscator->decode('k'); // 10
$obfuscator->decode('eTAg'); // 1234566
```

Using a salt

```php
$obfuscator = new IdObfuscator();
$obfuscator->encode(10, 'test'); // m
$obfuscator->encode(1234566, 'test'); // oK-k

$obfuscator->decode('m', 'test'); // 10
$obfuscator->decode('oK-k', 'test'); // 1234566
```

Custom character set 

(default is `abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890-_`)

```php
$obfuscator = new IdObfuscator('0123456789abcdef');
$obfuscator->encode(16); // 10 (string)
```

## TODO
- Some unfortunate IDs might generate swear word: find a way to avoid them.
- Add support for big numbers.
