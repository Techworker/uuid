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

use Techworker\Uuid\ByteList;
use Techworker\Uuid\ByteProvider\ByteProviderInterface;

/**
 * This provider extracts all 2-pair hex digits from the given string. It
 * works case-insensitive.
 *
 * @package    Techworker\Uuid
 * @subpackage ByteProvider
 * @author     Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @copyright  2014 Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @license    MIT
 * @link       http://www.techworker.de/
 * @see        http://www.ietf.org/rfc/rfc4122.txt
 */
class Hex implements ByteProviderInterface
{
    /**
     * The string with possible hex characters in it.
     *
     * @var string
     */
    protected $string;

    /**
     * Creates a new instance of the \Techworker\Uuid\ByteProvider\HexString
     * provider.
     *
     * @param string $string The string
     */
    public function __construct($string)
    {
        $this->string = $string;
    }

    /**
     * Fetches all 2-digit hex chars from the string and creates a ByteList
     * from them.
     *
     * @return ByteList
     */
    public function provide()
    {
        preg_match_all("/[0-9A-F]{2}/i", $this->string, $parts);
        return ByteList::factoryFromArray($parts[0], true);
   }
}