<?php
/**
 * This file is part of the Techworker\Uuid package.
 *
 * (c) Benjamin Ansbach <benjaminansbach@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Techworker\Uuid;

/**
 * Class with static utility functions.
 *
 * @package    Techworker\Uuid
 * @author     Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @copyright  2014 Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @license    MIT
 * @link       http://www.techworker.de/
 */
class Util
{
    /**
     * Performs an unsigned right shift (known as >>> in other languages).
     *
     * @link http://stackoverflow.com/a/14428473
     * @param int $a value 1
     * @param int $b value 2
     * @return int
     */
    public static function unsignedRightShift($a, $b)
    {
        if($b == 0) return $a;
        return ($a >> $b) & ~(1 << (8 * PHP_INT_SIZE - 1) >> ($b - 1));
    }

    /**
     * Returns a zero padded hex version of an int.
     *
     * @param int $value The value to get the hex from.
     * @param string $length The pad length.
     * @return string
     */
    public static function paddedDecHex($value, $length)
    {
        return sprintf("%0" . $length . "x", $value);
    }
}