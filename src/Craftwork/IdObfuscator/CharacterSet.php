<?php
declare(strict_types=1);

namespace Craftwork\IdObfuscator;

use Craftwork\IdObfuscator\Exception\DuplicateCharacterException;
use Craftwork\IdObfuscator\Exception\InvalidCharacterSetException;
use Craftwork\IdObfuscator\Exception\InvalidCharacterSetLengthException;

final class CharacterSet
{
    const CHARSET_DEFAULT = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890-_';

    /**
     * @var string
     */
    private $characters;

    /**
     * @param string $characters
     */
    private function __construct(string $characters)
    {
        $this->assertAllAsciiCharacters($characters);
        $this->assertCharactersLength($characters);
        $this->assertNoDuplicateCharacters($characters);

        $this->characters = $characters;
    }

    /**
     * @param string $characters
     *
     * @return CharacterSet
     *
     * @throws InvalidCharacterSetLengthException
     * @throws DuplicateCharacterException
     * @throws InvalidCharacterSetException
     */
    public static function ofCustomCharacters(string $characters): CharacterSet
    {
        return new self($characters);
    }

    /**
     * @return CharacterSet
     */
    public static function ofDefaultCharacters(): CharacterSet
    {
        return new self(self::CHARSET_DEFAULT);
    }

    /**
     * @return string
     */
    public function getCharacters(): string
    {
        return $this->characters;
    }

    /**
     * @param string $salt
     * @return CharacterSet
     */
    public function shuffleCharacterSetWithSalt(string $salt): CharacterSet
    {
        $saltLength = strlen($salt);

        if ($saltLength === 0) {
            return $this;
        }

        $characterSet = $this->characters;

        for ($i = $this->length() - 1, $v = 0, $p = 0; $i > 0; $i--, $v++) {
            $v %= $saltLength;
            $int = ord($salt[$v]);
            $p += $int;
            $j = ($int + $v + $p) % $i;
            list($characterSet[$i], $characterSet[$j]) = [$characterSet[$j], $characterSet[$i]];
        }

        return new self($characterSet);
    }

    /**
     * @return int
     */
    public function length(): int
    {
        return strlen($this->characters);
    }

    /**
     * @param string $characters
     *
     * @throws InvalidCharacterSetLengthException
     */
    private function assertCharactersLength(string $characters)
    {
        if ('' === $characters || 2 > strlen($characters)) {
            throw new InvalidCharacterSetLengthException('$characters must be at least 2 characters long.');
        }
    }

    /**
     * @param string $characters
     *
     * @throws DuplicateCharacterException
     */
    private function assertNoDuplicateCharacters(string $characters)
    {
        if ($characters !== implode(array_keys(array_flip(str_split($characters))))) {
            throw new DuplicateCharacterException(
                sprintf('Duplicate characters found in character string %s.', $characters)
            );
        }
    }

    /**
     * @param string $characters
     *
     * @throws InvalidCharacterSetException
     */
    private function assertAllAsciiCharacters(string $characters)
    {
        if (1 === preg_match('/[\\x80-\\xff]+/', $characters)) {
            throw new InvalidCharacterSetException($characters);
        }
    }
}
