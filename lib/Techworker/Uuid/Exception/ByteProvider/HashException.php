<?php
/**
 * This file is part of the Techworker\Uuid package.
 *
 * (c) Benjamin Ansbach <benjaminansbach@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Techworker\Uuid\Exception\ByteProvider;

use \Techworker\Uuid\Exception;

/**
 * This exception gets thrown when value for an md5 or sha1 hash provider cannot be converted to string.
 *
 * @package    Techworker\Uuid
 * @subpackage Exception
 * @author     Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @copyright  2014 Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @license    MIT
 * @link       http://www.techworker.de/
 */
class HashException extends Exception
{
    /**
     * Constructor.
     *
     * Creates a new instance of the HashException
     *
     * @param string $hash
     */
    public function __construct($hash)
    {
        parent::__construct(
            "Unable to create a string from the given value in the
            " . $hash . " ByteProvider."
        );
    }
}