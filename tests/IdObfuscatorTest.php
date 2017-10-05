<?php
namespace Craftwork\IdObfuscator;

use PHPUnit\Framework\TestCase;

class IdObfuscatorTest extends TestCase
{
    public function testEncodeIdWithoutSalt()
    {
        $idObfuscator = $this->createIdObfuscator('');

        $this->assertEquals('b', $idObfuscator->encode(0));
        $this->assertEquals('cn', $idObfuscator->encode(64));
        $this->assertEquals('dz', $idObfuscator->encode(128));
    }

    public function testEncodeIdWithSalt()
    {
        $idObfuscator = $this->createIdObfuscator('test');

        $this->assertEquals('7', $idObfuscator->encode(0));
        $this->assertEquals('bQ', $idObfuscator->encode(64));
    }

    public function testEncodeIdWithHexCharacters()
    {
        $idObfuscator = $this->createIdObfuscator('test', CharacterSet::ofHexCharacters());

        $this->assertEquals('a2', $idObfuscator->encode(128));
    }

    public function testDecodeEncodedId()
    {
        $idObfuscator = $this->createIdObfuscator('test');

        $this->assertEquals(0, $idObfuscator->decode($idObfuscator->encode(0)));
        $this->assertEquals(64, $idObfuscator->decode($idObfuscator->encode(64)));
    }

    public function testEncodedWithCustomCharacterSet()
    {
        $idObfuscator = $this->createIdObfuscator('', CharacterSet::ofCustomCharacters('ambidextrously'));

        $this->assertEquals('mb', $idObfuscator->encode(16));
        $this->assertEquals('bd', $idObfuscator->encode(32));
    }

    /**
     * @param string       $salt
     * @param CharacterSet $characterSet
     *
     * @return IdObfuscator
     */
    private function createIdObfuscator(string $salt, CharacterSet $characterSet = null): IdObfuscator
    {
        return new IdObfuscator($salt, $characterSet);
    }
}
