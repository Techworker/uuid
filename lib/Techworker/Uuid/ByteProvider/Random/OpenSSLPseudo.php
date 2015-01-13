<?php
/**
 * This file is part of the Techworker\Uuid package.
 *
 * (c) Benjamin Ansbach <benjaminansbach@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Techworker\Uuid\ByteProvider\Random;

use Techworker\Uuid\ByteList;
use Techworker\Uuid\ByteProvider\String\Hex;
use Techworker\Uuid\ByteProvider\ByteProviderInterface;

/**
 * This provider creates a 16 Byte sized ByteList with random values provided
 * by the openssl_random_pseudo_bytes PHP function. You should note that you
 * have to install OpenSSL to use that function.
 *
 * @package    Techworker\Uuid
 * @subpackage ByteProvider
 * @author     Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @copyright  2014 Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @license    MIT
 * @link       http://www.techworker.de/
 * @see        http://www.ietf.org/rfc/rfc4122.txt
 * @see        http://us3.php.net/manual/en/openssl.requirements.php
 */
class OpenSSLPseudo implements ByteProviderInterface
{
    /**
     * Returns the 16 byte sized random bytelist.
     *
     * @return ByteList
     */
    public function provide()
    {
        $bytes = openssl_random_pseudo_bytes(16);
        return (new Hex(bin2hex($bytes)))->provide();
    }
}