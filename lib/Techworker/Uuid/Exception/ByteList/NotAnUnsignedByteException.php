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
 * This exception gets thrown you try to add a non unsigned byte to the ByteList.
 *
 * @package    Techworker\Uuid
 * @subpackage Exception
 * @author     Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @copyright  2014 Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @license    MIT
 * @link       http://www.techworker.de/
 */
class NotAnUnsignedByteException extends Exception
{
    /**
     * Constructor.
     *
     * Creates a new instance of the NotAnUnsignedByteException
     *
     * @param string $value
     */
    public function __construct($value)
    {
        parent::__construct(
            "You tried to add a value to a ByteCollection which cannot be " .
            "identified as an unsigned byte [0-255]. " .
            "(you provided = " . $value . ")"
        );
    }
}