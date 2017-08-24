<?php
namespace Craftwork\IdObfuscator;

use Craftwork\IdObfuscator\Exception\DuplicateCharacterException;
use Craftwork\IdObfuscator\Exception\InvalidCharacterSetException;
use Craftwork\IdObfuscator\Exception\InvalidCharacterSetLengthException;
use PHPUnit\Framework\TestCase;

class CharacterSetTest extends TestCase
{
    public function testThrowsInvalidCharacterSetLengthExceptionIfCustomCharacterSetTooShort()
    {
        $this->expectException(InvalidCharacterSetLengthException::class);
        CharacterSet::ofCustomCharacters('a');
    }

    public function testThrowsDuplicateCharacterExceptionIfCustomCharacterSetContainsDuplicateCharacters()
    {
        $this->expectException(DuplicateCharacterException::class);
        CharacterSet::ofCustomCharacters('abcda');
    }

    public function testThrowsInvalidCharacterSetExceptionIfCustomCharacterSetContainsNonAscii()
    {
        $this->expectException(InvalidCharacterSetException::class);
        CharacterSet::ofCustomCharacters('ß');
    }
}
