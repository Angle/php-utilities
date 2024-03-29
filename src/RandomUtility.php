<?php

namespace Angle\Utilities;

/**
 * ADAPTED FROM: https://defuse.ca/generating-random-passwords.htm by Defuse Security <https://defuse.ca>
 * @package Angle\Common\Utilities\Random
 */
abstract class RandomUtility
{
    /**
     * Generates an alphanumeric string
     * @param int $length number of characters in the password
     * @param boolean $human true for a simpler human-readable non-clashing character set
     * @return string
     */
    public static function generateString($length = 12, $human = false)
    {
        if ($human) {
            $characters = '0123456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        } else {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }

        return self::getCustomPassword(str_split($characters), $length);
    }

    /**
     * Generates a password from a Printable ASCII safe character set
     * @param int $length number of characters in the password
     * @return string
     */
    public static function getASCIIPassword($length)
    {
        $printable = "!\"#$%&'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\]^_`abcdefghijklmnopqrstuvwxyz{|}~";
        return self::getCustomPassword(str_split($printable), $length);
    }

    /**
     * Generates a password from a ASCII Alphanumerical character set (a-z|A-Z|0-9)
     * @param int $length number of characters in the password
     * @return string
     */
    public static function getAlphaNumericPassword($length)
    {
        $alphanum = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        return self::getCustomPassword(str_split($alphanum), $length);
    }

    /**
     * Generates a password from the english alphabet
     * @param int $length number of characters in the password
     * @param boolean $lower true for lower case
     * @return string
     */
    public static function getAlphabetPassword($length, $lower=true)
    {
        if ($lower) {
            $alphanum = "abcdefghijklmnopqrstuvwxyz";
        } else {
            $alphanum = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        }
        return self::getCustomPassword(str_split($alphanum), $length);
    }

    /**
     * Generates a hex password (0-f)
     * @param int $length number of characters in the password
     * @return string
     */
    public static function getHexPassword($length)
    {
        $hex = "0123456789abcdef";
        return self::getCustomPassword(str_split($hex), $length);
    }

    /**
     * Create a random password composed of a custom character set.
     * @param array $characterSet - An array of strings the password can be composed of.
     * @param int $length - The number of random strings (in $characterSet) to include in the password.
     * @return string|false false on error
     */
    public static function getCustomPassword(array $characterSet, $length)
    {
        if ($length < 1 || !is_array($characterSet)) {
            return false;
        }
        
        $charSetLen = count($characterSet);
        
        if ($charSetLen == 0) {
            return false;
        }

        $randomInts = self::getRandomInts($length * 2);
        $mask = self::getMinimalBitMask($charSetLen - 1);
        $password = "";

        // To generate the password, we repeatedly try random integers and use the ones within the range
        // 0 to $charSetLen - 1 to select an index into the character set. This is the only known way to
        // make a truly unbiased random selection from a set using random binary data.
        // A poorly implemented or malicious RNG could cause an infinite loop, leading to a denial of service.
        // We need to guarantee termination, so $iterLimit holds the number of further iterations we will allow.
        // It is extremely unlikely (about 2^-64) that more than $length*64 random ints are needed.
        $iterLimit = max($length, $length * 64);   // If length is close to PHP_INT_MAX we don't want to overflow.
        $randIdx = 0;

        while (strlen($password) < $length) {
            // Pull new random ints if we've gone through the current array
            if ($randIdx >= count($randomInts)) {
                $randomInts = self::getRandomInts(2*($length - strlen($password)));
                $randIdx = 0;
            }

            // This is wasteful, but RNGs are fast and doing otherwise adds complexity and bias.
            $c = $randomInts[$randIdx++] & $mask;

            // Only use the random number if it is in range, otherwise try another (next iteration).
            if ($c < $charSetLen) {
                $r = self::sidechannel_safe_array_index($characterSet, $c);
                if ($r !== false) $password .= $r;
            }

            // Guarantee termination, prevent infinite loops
            $iterLimit--;
            if ($iterLimit <= 0) {
                //throw new \RuntimeException('Exceeded maximum iterations when generating the random string');
                return false;
            }
        }

        return $password;
    }

    /**
     * Returns the character at index in constant time.
     * @param array $characterSet
     * @param int $index
     * @return string char
     */
    private static function sidechannel_safe_array_index(array $characterSet, $index)
    {
        // FIXME: Make the const-time hack below work for all integer sizes, or check it properly.
        if (count($characterSet) > 65535 || $index > count($characterSet)) {
            return false;
        }

        $character = 0;
        for ($i = 0; $i < count($characterSet); $i++) {
            $x = $i ^ $index;
            $mask = (((($x | ($x >> 16)) & 0xFFFF) + 0xFFFF) >> 16) - 1;
            $character |= ord($characterSet[$i]) & $mask;
        }
        return chr($character);
    }

    /**
     * Returns the smallest bit mask of all 1s such that ($toRepresent & mask) = $toRepresent.
     * $toRepresent must be an integer greater than or equal to 1.
     */
    private static function getMinimalBitMask($toRepresent)
    {
        if ($toRepresent < 1) return false;

        $mask = 0x1;
        while ($mask < $toRepresent) {
            $mask = ($mask << 1) | 1;
        }

        return $mask;
    }

    /**
     * Returns an array of $numInts random integers between 0 and PHP_INT_MAX
     * @param $numInts number of random integers
     * @return array
     */
    private static function getRandomInts($numInts)
    {
        $ints = array();
        if ($numInts <= 0) {
            return $ints;
        }

        try {
            $rawBinary = random_bytes($numInts * PHP_INT_SIZE);
        } catch (\Exception $e) {
            throw new \RuntimeException('Not enough entropy to initialize the random_bytes() functions');
        }

        for ($i = 0; $i < $numInts; ++$i) {
            $thisInt = 0;
            for ($j = 0; $j < PHP_INT_SIZE; ++$j) {
                $thisInt = ($thisInt << 8) | (ord($rawBinary[$i * PHP_INT_SIZE + $j]) & 0xFF);
            }
            // Absolute value in two's compliment (with min int going to zero)
            $thisInt = $thisInt & PHP_INT_MAX;
            $ints[] = $thisInt;
        }

        return $ints;
    }
}