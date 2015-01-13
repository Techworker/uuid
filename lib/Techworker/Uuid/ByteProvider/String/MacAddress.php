<?php
/**
 * This file is part of the Techworker\Uuid package.
 *
 * (c) Benjamin Ansbach <benjaminansbach@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Techworker\Uuid\ByteProvider\String;

/**
 * This provider extends the Hex String ByteProvider and extracts all 2-pair
 * hex digits from the given mac address. This class is here just for code
 * readability.
 *
 * @package    Techworker\Uuid
 * @subpackage ByteProvider
 * @author     Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @copyright  2014 Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @license    MIT
 * @link       http://www.techworker.de/
 * @see        http://www.ietf.org/rfc/rfc4122.txt
 */
class MacAddress extends Hex
{
    /**
     * Constructor.
     *
     * Creates a new instance of the Techworker\Uuid\ByteProvider\MacAddress
     *
     * @param string $macAddress
     */
    public function __construct($macAddress)
    {
        parent::__construct($macAddress);
    }
}