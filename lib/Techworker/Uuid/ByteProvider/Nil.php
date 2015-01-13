<?php
/**
 * This file is part of the Techworker\Uuid package.
 *
 * (c) Benjamin Ansbach <benjaminansbach@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Techworker\Uuid\ByteProvider;

use Techworker\Uuid\ByteList;

/**
 * This provider creates a 16 Byte sized ByteList where each Byte equals zero.
 *
 * @package    Techworker\Uuid
 * @subpackage ByteProvider
 * @author     Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @copyright  2014 Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @license    MIT
 * @link       http://www.techworker.de/
 * @see        http://www.ietf.org/rfc/rfc4122.txt
 */
class Nil implements ByteProviderInterface
{
    /**
     * Provides the empty ByteList.
     *
     * @return ByteList
     */
    public function provide()
    {
        return ByteList::factoryFromArray(array_fill(0, 16, 0));
   }
}