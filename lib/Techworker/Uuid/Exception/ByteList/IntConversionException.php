<?php
/**
 * This file is part of the Techworker\Uuid package.
 *
 * (c) Benjamin Ansbach <benjaminansbach@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Techworker\Uuid\Exception\ByteList;

use \Techworker\Uuid\Exception;

/**
 * This exception gets thrown you try to convert byte collection to an int but do not have enough bytes.
 *
 * @package    Techworker\Uuid
 * @subpackage Exception
 * @author     Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @copyright  2014 Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @license    MIT
 * @link       http://www.techworker.de/
 */
class IntConversionException extends Exception
{
    /**
     * Constructor.
     *
     * Creates a new instance of the IntConversionException
     *
     * @param string $to
     * @param int $count
     */
    public function __construct($to, $count)
    {
        parent::__construct(
            "You tried to create an " . $to . " from a ByteCollection with " .
            "less or more than 4 bytes. (size = " . $count . " values)"
        );
    }
}