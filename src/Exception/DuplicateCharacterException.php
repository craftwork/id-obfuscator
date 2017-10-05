<?php
declare(strict_types=1);

namespace Craftwork\IdObfuscator\Exception;

class DuplicateCharacterException extends \InvalidArgumentException implements IdObfuscatorExceptionInterface
{
}
