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

    public function testThrowsInvalidCharacterSetExceptionIfCustomCharacterSetContainsExtendedAscii()
    {
        $this->expectException(InvalidCharacterSetException::class);
        CharacterSet::ofCustomCharacters(chr(128) . chr(129));
    }

    public function testThrowsInvalidCharacterSetExceptionIfCustomCharacterSetContainsUtf8()
    {
        $this->expectException(InvalidCharacterSetException::class);
        CharacterSet::ofCustomCharacters('ßßß');
    }
}
