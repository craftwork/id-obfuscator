<?php
declare(strict_types=1);

namespace Craftwork\IdObfuscator;

use Craftwork\IdObfuscator\Exception\DuplicateCharacterException;
use Craftwork\IdObfuscator\Exception\InvalidCharacterSetException;
use Craftwork\IdObfuscator\Exception\InvalidCharacterSetLengthException;

final class CharacterSet
{
    const CHARSET_DEFAULT = 'bcdfghjklmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ1234567890-_';
    const CHARSET_HEX = '0123456789abcdef';

    /**
     * @var string
     */
    private $characters;

    /**
     * @var int
     */
    private $length;

    /**
     * @param string $characters
     *
     * @throws DuplicateCharacterException
     * @throws InvalidCharacterSetException
     * @throws InvalidCharacterSetLengthException
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
     * @return self
     *
     * @throws InvalidCharacterSetLengthException
     * @throws DuplicateCharacterException
     * @throws InvalidCharacterSetException
     */
    public static function ofCustomCharacters(string $characters): self
    {
        return new self($characters);
    }

    /**
     * @return self
     */
    public static function ofDefaultCharacters(): self
    {
        return new self(self::CHARSET_DEFAULT);
    }

    /**
     * @return self
     */
    public static function ofHexCharacters(): self
    {
        return new self(self::CHARSET_HEX);
    }

    /**
     * @return string
     */
    public function getCharacters(): string
    {
        return $this->characters;
    }

    /**
     * Creates a new instance using the same character set, but shuffled with the salt to obscure how the ID is
     * generated.
     *
     * @param string $salt
     *
     * @return self
     */
    public function shuffleWithSalt(string $salt): self
    {
        $saltLength = strlen($salt);

        if ($saltLength === 0) {
            return new self($this->characters);
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
        if ($this->length === null) {
            $this->length = strlen($this->characters);
        }

        return $this->length;
    }

    /**
     * @param string $characters
     *
     * @throws InvalidCharacterSetLengthException
     */
    private function assertCharactersLength(string $characters)
    {
        if (strlen($characters) < 2) {
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
        if ($characters !== implode(array_unique(str_split($characters)))) {
            throw new DuplicateCharacterException(
                sprintf('Duplicate characters found in character string: %s', $characters)
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
        if (preg_match('/[\\x80-\\xff]+/', $characters) === 1) {
            throw new InvalidCharacterSetException(
                sprintf('Character set has characters outside of the ASCII range: %s', $characters)
            );
        }
    }
}
