<?php
namespace Craftwork\IdObfuscator;

final class IdObfuscator
{
    const CHARSET_DEFAULT = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890-_';

    /**
     * @var string
     */
    private $characterSet;

    /**
     * @var int
     */
    private $characterSetLength;

    /**
     * @param string $salt Salt that should be unique and kept secret for your application.
     * @param string $characterSet An optional character set.
     */
    public function __construct(string $salt, string $characterSet = self::CHARSET_DEFAULT)
    {
        $this->characterSet = $this->shuffleCharacterSetWithSalt($characterSet, $salt);
        $this->characterSetLength = strlen($this->characterSet);
    }

    /**
     * @param int $number The ID/number to encode.
     * @return string The obfuscated ID.
     */
    public function encode(int $number) : string
    {
        $hash = '';

        do {
            $hash = $this->characterSet[$number % $this->characterSetLength] . $hash;
            $number = (int) ($number / $this->characterSetLength);
        } while ($number > 0);

        return $hash;
    }

    /**
     * @param string $string This will be the obfuscated ID as returned by self::encode.
     * @return int The original ID.
     */
    public function decode(string $string) : int
    {
        return array_reduce(str_split($string), function ($number, $char) {
            return ($number * $this->characterSetLength) + strpos($this->characterSet, $char);
        }, 0);
    }

    private function shuffleCharacterSetWithSalt(string $characterSet, string $salt): string
    {
        $saltLength = strlen($salt);

        if ($saltLength === 0) {
            return $characterSet;
        }

        for ($i = strlen($characterSet) - 1, $v = 0, $p = 0; $i > 0; $i--, $v++) {
            $v %= $saltLength;
            $int = ord($salt[$v]);
            $p += $int;
            $j = ($int + $v + $p) % $i;
            list($characterSet[$i], $characterSet[$j]) = [$characterSet[$j], $characterSet[$i]];
        }

        return $characterSet;
    }
}
