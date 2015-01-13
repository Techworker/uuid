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
 * This exception gets thrown by the ByteList when the given offset is not an int.
 *
 * @package    Techworker\Uuid
 * @subpackage Exception
 * @author     Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @copyright  2014 Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @license    MIT
 * @link       http://www.techworker.de/
 */
class OffsetNotValidException extends Exception
{
    /**
     * Constructor.
     *
     * Creates a new instance of the OffsetNotValidException
     *
     * @param string $offset
     */
    public function __construct($offset)
    {
        parent::__construct(
            "You can only use int based offsets in a byte list " .
            "(you used = " . $offset . ")"
        );
    }
}