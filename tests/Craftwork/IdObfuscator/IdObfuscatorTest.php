<?php
namespace Craftwork\IdObfuscator;

use PHPUnit\Framework\TestCase;

class IdObfuscatorTest extends TestCase
{
    /**
     * @var IdObfuscator
     */
    private $idObfuscator;

    public function testEncodeIdWithoutSalt()
    {
        $this->idObfuscator = $this->createIdObfuscator('');

        $this->assertSame('a', $this->idObfuscator->encode(0));
        $this->assertSame('ba', $this->idObfuscator->encode(64));
        $this->assertSame('ca', $this->idObfuscator->encode(128));
    }

    public function testEncodeIdWithSalt()
    {
        $this->idObfuscator = $this->createIdObfuscator('test');

        $this->assertSame('L', $this->idObfuscator->encode(0));
        $this->assertSame('sL', $this->idObfuscator->encode(64));
    }

    public function testDecodeEncodedId()
    {
        $this->idObfuscator = $this->createIdObfuscator('test');

        $this->assertSame(0, $this->idObfuscator->decode($this->idObfuscator->encode(0)));
        $this->assertSame(64, $this->idObfuscator->decode($this->idObfuscator->encode(64)));
    }

    public function testEncodedWithCustomCharacterSet()
    {
        $this->idObfuscator = $this
            ->createIdObfuscator('', CharacterSet::ofCustomCharacters('0123456789abcdef'));

        $this->assertSame('10', $this->idObfuscator->encode(16));
        $this->assertSame('20', $this->idObfuscator->encode(32));
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
