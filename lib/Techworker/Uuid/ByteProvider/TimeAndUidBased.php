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

use Techworker\Uuid;

/**
 * This provider creates a 10 Byte sized time based ByteList but overwrites the first 4 bytes with the given uid. This
 * is as vaguely described in the RFC4122 for UUID V2 so I copied that functionality from other various implementations
 * out there. You can use posix_getuid() on Unix systems.
 *
 * @package    Techworker\Uuid
 * @subpackage ByteProvider
 * @author     Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @copyright  2014 Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @license    MIT
 * @link       http://www.techworker.de/
 * @see        http://www.ietf.org/rfc/rfc4122.txt
 */
class TimeAndUidBased extends TimeBased implements ByteProviderInterface
{
    private $uid;

    /**
     * Constructor.
     *
     * Creates a new instance of the Techworker\Uuid\ByteProvider\TimeAndUidBased ByteProvider.
     *
     * @param int $uid The uid to embed into the returned bytelist.
     */
    public function __construct($uid)
    {
        $this->uid = $uid;
        parent::__construct();
    }

    /**
     * Calls the TimeBased provide method and embeds the UID.
     *
     * @return Uuid\ByteList
     */
    public function provide()
    {
        $bytes = parent::provide();
        $uid = unpack("C*", pack("L", $this->uid));
        $bytes->replace(0, array_values($uid)); // unpack starts with index "1"..

        return $bytes;
    }
}