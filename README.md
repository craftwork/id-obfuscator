# ID Obfuscator

[![Build status](https://img.shields.io/travis/craftwork/id-obfuscator.svg?style=flat-square)](https://travis-ci.org/craftwork/id-obfuscator.svg?branch=master)
[![Code coverage](https://img.shields.io/codecov/c/github/craftwork/id-obfuscator.svg?style=flat-square)](https://codecov.io/gh/craftwork/id-obfuscator)
[![Code quality](https://img.shields.io/scrutinizer/g/craftwork/id-obfuscator.svg?style=flat-square)](https://codecov.io/gh/craftwork/id-obfuscator)
[![Packagist](https://img.shields.io/packagist/v/craftwork/id-obfuscator.svg?style=flat-square)](https://packagist.org/packages/craftwork/id-obfuscator)
[![Chat](https://img.shields.io/gitter/room/nwjs/nw.js.svg?style=flat-square)](https://gitter.im/craftwork/Lobby)

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
 
You can use a custom character set and a salt to shuffle the characters. Keep the salt private.

It is not advisable to change these at run time as some users might share or bookmark links. To ensure users don't end up on broken links always use the same character set and salt. If the links are restricted to logged in users it might be a good idea to use a user specific salt, such as a uuid.

## Installation

```
$ composer require craftwork/id-obfuscator
```

## Requirements

PHP 7.0.

## Usage

```php
$salt = 'test';
$obfuscator = new IdObfuscator($salt);
$obfuscator->encode(10); // m
$obfuscator->encode(1234566); // oK-k

$obfuscator->decode('m'); // 10
$obfuscator->decode('oK-k'); // 1234566
```

### Character sets

The default character set is `bcdefghjklmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ1234567890-_`. An additional character set
is also built in which only uses lowercase hexadecimal characters (`CharacterSet::ofHexCharacters()`).

You can provide your own character set if you want to either limit the encoding to certain characters or use different
characters. Characters need to be ASCII and the character set must be at least two characters long and not contain any
duplicates.

```php
$salt = 'test';
$customCharacterSet = CharacterSet::ofCustomCharacters('ambidextrously');
$obfuscator = new IdObfuscator($salt, $customCharacterSet);
$obfuscator->encode(16); // 10 (string)
```
