<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\Type;

use InvalidArgumentException;

class VarInt extends AbstractType
{
    public const MIN_VALUE = -2147483648;

    public const MAX_VALUE = 2147483647;

    private function __construct()
    {
    }

    public static function pack(int $value): string
    {
        if ($value < self::MIN_VALUE || $value > self::MAX_VALUE) {
            throw new InvalidArgumentException(sprintf('%s is outside the range of VarInt', $value));
        }
        $buffer = str_repeat("\0", self::size($value, false));
        $current = 0;

        $high = 0;
        $low = $value;

        while (($low >= 0x80 || $low < 0) || 0 != $high) {
            $buffer[$current] = \chr($low | 0x80);
            $value = ($value >> 7) & ~(0x7F << ((\PHP_INT_SIZE << 3) - 7));
            $carry = ($high & 0x7F) << ((\PHP_INT_SIZE << 3) - 7);
            $high = ($high >> 7) & ~(0x7F << ((\PHP_INT_SIZE << 3) - 7));
            $low = (($low >> 7) & ~(0x7F << ((\PHP_INT_SIZE << 3) - 7)) | $carry);
            ++$current;
        }
        $buffer[$current] = \chr($low);

        return $buffer;
    }

    public static function unpack(string $value, ?int &$size = null): int
    {
        $intValue = VarLong::unpack($value, $size);
        $intValue &= 0xFFFFFFFF;

        // Convert large uint32 to int32.
        if ($intValue > 0x7FFFFFFF) {
            $intValue = $intValue | (0xFFFFFFFF << 32);
        }

        return (int) $intValue;
    }

    public static function size(int $value, bool $signExtended = false): int
    {
        if ($value < 0) {
            if ($signExtended) {
                return 10;
            } else {
                return 5;
            }
        }
        if ($value < (1 << 7)) {
            return 1;
        }
        if ($value < (1 << 14)) {
            return 2;
        }
        if ($value < (1 << 21)) {
            return 3;
        }
        if ($value < (1 << 28)) {
            return 4;
        }

        return 5;
    }
}
