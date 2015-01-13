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
 * This exception gets thrown when you try to add a value to a limited ByteList and the list is full.
 *
 * @package    Techworker\Uuid
 * @subpackage Exception
 * @author     Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @copyright  2014 Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @license    MIT
 * @link       http://www.techworker.de/
 */
class MaxSizeExceededException extends Exception
{
    /**
     * Constructor.
     *
     * Creates a new instance of the MaxSizeExceededException
     *
     * @param string $maxSize
     */
    public function __construct($maxSize)
    {
        parent::__construct(
            "You tried to add too many bytes to a limited ByteCollection " .
            "(maximum size = " . $maxSize . ")"
        );
    }
}