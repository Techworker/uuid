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

use Techworker\Uuid\ByteProvider\Random\MtRand;

/**
 * This provider creates a 10 Byte sized time based ByteList as described in the RFC4122 for UUID V1. Since we dont
 * have nano second precision in phps time methods we emulate the nanoseconds by incrementing a counter based on the
 * microtime.
 *
 * @package    Techworker\Uuid
 * @subpackage ByteProvider
 * @author     Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @copyright  2014 Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @license    MIT
 * @link       http://www.techworker.de/
 * @see        http://www.ietf.org/rfc/rfc4122.txt
 */
class TimeBased implements ByteProviderInterface
{
    /**
     * The microseconds when the provider was last used (in the current process of course).
     *
     * @var int
     */
    protected static $lastMicrotime = 0;

    /**
     * The emulated nano second counter which will be incremented by one each time the microseconds
     * did not change while generating > 1 UUID.
     *
     * @var int
     */
    protected static $nanoSequence = 0;

    /**
     * The clock sequence that is added to the uuid.
     *
     * @var int
     */
    protected static $clockSeq;

    /**
     * Constructor
     *
     * Creates a new instance of the \Techworker\Uuid\ByteProvider\TimeBased
     * class and initializes the static clock sequence with random values.
     */
    public function __construct()
    {
        // initialize the cloc sequence with
        if(is_null(self::$clockSeq))
        {
            $rnd = (new MtRand())->provide();
            self::$clockSeq = ($rnd->getAt(6) << 8 | $rnd->getAt(7)) & 0x3fff;
        }
    }

    /**
     * Returns a 10 Byte length ByteList with the time based bytes as described
     * in RFC4122 for the UUID V1.
     *
     * @return Uuid\ByteList
     */
    public function provide()
    {
        // fetch microtime and update the nano sequence.
        $microtime = round(microtime(true) * 1000);
        $nanoSequence = self::$nanoSequence + 1;

        $dt = ($microtime - self::$lastMicrotime) +
            ($nanoSequence - self::$nanoSequence) / 10000;

        if($dt < 0) {
            self::$clockSeq = self::$clockSeq + 1 & 0x3fff;
        }

        // reset the nano sequence, we have a new microtime
        if ($dt < 0 || $microtime > self::$lastMicrotime) {
            $nanoSequence = 0;
        }

        // nano sequence is extended - i dont think this will actually happen
        if ($nanoSequence >= 10000)
        {
            throw new Uuid\Exception(
                "Unable to create the timebased UUID part (possibly V1) " .
                "because you tried to create more than 10000 UUIDs / " .
                "microsecond");
        }

        // update static var holders
        self::$lastMicrotime = $microtime;
        self::$nanoSequence = $nanoSequence;

        // add the seconds from 1582 to 1970
        $microtime += 12219292800000;

        // create new bytelist
        $b = new Uuid\ByteList(10);

        // Set the time_low field equal to the least significant 32 bits
        // (bits zero through 31) of the timestamp in the same order of
        // significance.
        $microNano = (($microtime & 0xfffffff) * 10000 + $nanoSequence);
        $tl = $microNano % 0x100000000;
        $b->append(unpack("C*", pack("N", $tl)));

        // create time_mid field
        $tmh = ($microtime / 0x100000000 * 10000) & 0xfffffff;

        $b->append(unpack("C*", pack("n", $tmh)));
        // create time_high_and_version field
        $b->append(unpack("C2", pack("N", $tmh)));

        // create clock_seq_hi_and_reserved field
        $b->append(unpack("C", pack("n", self::$clockSeq)));

        // create clock_seq_low field
        $b->append(unpack("C*", pack("C", self::$clockSeq)));

        return $b;
    }
}