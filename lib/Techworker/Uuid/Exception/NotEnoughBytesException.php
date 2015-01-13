<?php
/**
 * This file is part of the Techworker\Uuid package.
 *
 * (c) Benjamin Ansbach <benjaminansbach@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Techworker\Uuid\Exception;

use \Techworker\Uuid\Exception;

/**
 * This exception gets thrown when a Uuid does not have enough bytes.
 *
 * @package    Techworker\Uuid
 * @subpackage Exception
 * @author     Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @copyright  2014 Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @license    MIT
 * @link       http://www.techworker.de/
 */
class NotEnoughBytesException extends Exception
{
    /**
     * Constructor.
     *
     * Creates a new instance of the NotEnoughBytesException
     *
     * @param int $numBytes
     */
    public function __construct($numBytes)
    {
        parent::__construct(
            "You tried to create an UUID but there is not enough information " .
            "(current uuid byte size = " . $numBytes . " values)"
        );
    }
}