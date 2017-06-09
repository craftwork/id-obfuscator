<?php

namespace spec\LuxPhp\IdObfuscator;

use LuxPhp\IdObfuscator\IdObfuscator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class IdObfuscatorSpec extends ObjectBehavior
{
    function it_encodes_an_integer_to_string()
    {
        $this->encode(0)->shouldReturn('a');
        $this->encode(64)->shouldReturn('ba');
        $this->encode(128)->shouldReturn('ca');
    }

    function it_decodes_a_string_to_integer()
    {
        $this->decode('a')->shouldReturn(0);
        $this->decode('ba')->shouldReturn(64);
        $this->decode('ca')->shouldReturn(128);
    }

    function it_encodes_an_integer_to_string_with_provided_salt()
    {
        $this->encode(0, 'test')->shouldReturn('L');
        $this->encode(64, 'test')->shouldReturn('sL');
    }

    function it_decodes_an_integer_to_string_with_provided_salt()
    {
        $this->decode('L', 'test')->shouldReturn(0);
        $this->decode('sL', 'test')->shouldReturn(64);
    }

    function it_encode_an_integer_with_custom_character_set()
    {
        $this->beConstructedWith('0123456789abcdef');
        $this->encode(16)->shouldReturn('10');
    }
}
