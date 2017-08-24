<?php
declare(strict_types=1);

namespace Craftwork\IdObfuscator;

final class IdObfuscator
{
    /**
     * @var CharacterSet
     */
    private $characterSet;

    /**
     * @param string       $salt Salt that should be unique and kept secret for your application.
     * @param CharacterSet $characterSet An optional character set.
     */
    public function __construct(string $salt, CharacterSet $characterSet = null)
    {
        $this->characterSet = (null === $characterSet ? CharacterSet::ofDefaultCharacters() : $characterSet)
            ->shuffleCharacterSetWithSalt($salt);
    }

    /**
     * @param int $number The ID/number to encode.
     * @return string The obfuscated ID.
     */
    public function encode(int $number): string
    {
        $hash = '';

        do {
            $hash = $this->characterSet->getCharacters()[$number % $this->characterSet->length()] . $hash;
            $number = (int) ($number / $this->characterSet->length());
        } while ($number > 0);

        return $hash;
    }

    /**
     * @param string $string This will be the obfuscated ID as returned by self::encode.
     * @return int The original ID.
     */
    public function decode(string $string): int
    {
        return array_reduce(str_split($string), function (int $number, string $char) {
            return ($number * $this->characterSet->length()) + strpos($this->characterSet->getCharacters(), $char);
        }, 0);
    }
}
