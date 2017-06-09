<?php

namespace LuxPhp\IdObfuscator;

class IdObfuscator
{
    /**
     * @var string
     */
    private $characterSet;

    /**
     * @var int
     */
    private $characterSetLength;

    /**
     * IdObfuscator constructor.
     *
     * @param string $characterSet An optional character set.
     */
    public function __construct(string $characterSet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890-_')
    {
        $this->characterSet = $characterSet;
        $this->characterSetLength = strlen($this->characterSet);

        if (!empty($salt)) {
            $this->characterSet = $this->shuffleCharacterSetWithSalt($salt);
        }
    }

    /**
     * @param int $number The number to  encode.
     * @param string|null $salt An optional salt string to shuffle the character set.
     *
     * @return string
     */
    public function encode(int $number, string $salt = null) : string
    {
        $hash = '';
        $characterSet = $this->shuffleCharacterSetWithSalt($salt);

        do {
            $reminder = $number % $this->characterSetLength;
            $number = (int) ($number / $this->characterSetLength);

            $hash .= $characterSet[$reminder];
        } while ($number > 0);

        return strrev($hash);
    }

    /**
     * @param string $string
     * @param string|null $salt The same salt used to encode, if any.
     *
     * @return int
     */
    public function decode(string $string, string $salt = null) : int
    {
        $number = 0;
        $characterSet = $this->shuffleCharacterSetWithSalt($salt);

        $inputChars = str_split($string);

        foreach ($inputChars as $char) {
            $position = strpos($characterSet, $char);
            $number = ($number * $this->characterSetLength) + $position;
        }

        return $number;
    }

    /**
     * @param $salt
     *
     * @return string
     */
    private function shuffleCharacterSetWithSalt($salt)
    {
        if (empty($salt)) {
            return $this->characterSet;
        }

        $saltLength = strlen($salt);
        $set = $this->characterSet;

        for ($i = $this->characterSetLength - 1, $v = 0, $p = 0; $i > 0; $i--, $v++) {
            $v %= $saltLength;
            $p += $int = ord($salt[$v]);
            $j = ($int + $v + $p) % $i;
            $temp = $set[$j];
            $set[$j] = $set[$i];
            $set[$i] = $temp;
        }

        return $set;
    }
}
