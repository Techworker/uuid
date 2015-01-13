<?php
/**
 * This file is part of the Techworker\Uuid package.
 *
 * (c) Benjamin Ansbach <benjaminansbach@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Techworker\Uuid\Exposer;

use Techworker\Uuid;
use Techworker\Uuid\ByteList;

/**
 * This class retrieves all - as defined by RFC4122 - data of an UUID.
 *
 * @package    Techworker\Uuid
 * @subpackage Exposer
 * @author     Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @copyright  2014 Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @license    MIT
 * @link       http://www.techworker.de/
 */
class Core extends AbstractExposer implements ExposerInterface
{
    /**
     * Gets "core".
     *
     * @return string
     */
    public function key()
    {
        return 'core';
    }

    /**
     * Gets the version of the UUID.
     *
     * @return int
     */
    public function getVersion()
    {
        return $this->uuid->getAt(6) >> 4;
    }

    /**
     * Gets a string representation of the used variant.
     *
     * @param int $variant The variant as defined in Uuid::VARIANT_* constant
     *  or another one.
     * @return string
     */
    public function describeVariant($variant)
    {
        switch($variant)
        {
            case Uuid::VARIANT_NCS:
                return "reserved for NCS compatibility";

            case Uuid::VARIANT_MICROSOFT:
                return "reserved for Microsoft compatibility";

            case Uuid::VARIANT_RFC_4122:
                return "specified in RFC 4122";

            case Uuid::VARIANT_FUTURE:
                return "reserved for future definition";

            default:
                return "unknown";
        }
    }

    /**
     * Gets the variant from the given UUID.
     *
     * @return int
     */
    public function getVariant()
    {
        $identifier = $this->uuid->getAt(8);

        if(!($identifier & Uuid::VARIANT_NCS))
            return Uuid::VARIANT_NCS;
        elseif(!($identifier & Uuid::VARIANT_RFC_4122))
            return Uuid::VARIANT_RFC_4122;
        elseif(!($identifier & 0x20))
            return Uuid::VARIANT_MICROSOFT;
        else
            return Uuid::VARIANT_FUTURE;
    }

    /**
     * Checks whether the current variant matches the given variant.
     *
     * @param int $variant The variant to check.
     * @return bool
     */
    public function isVariant($variant)
    {
        return $this->getVariant() === $variant;
    }

    /**
     * Gets the time_low field value as defined in RFC.
     *
     * @param bool $asHex Hex representation.
     * @return int|string
     */
    public function getTimeLow($asHex = true)
    {
        $timeLow = $this->uuid->slice(0, 4)->toInt32();
        if($asHex) return Uuid\Util::paddedDecHex($timeLow, 8);

        return $timeLow;
    }

    /**
     * Gets the time_mid field value as defined in RFC.
     *
     * @param bool $asHex Hex representation.
     * @return int|string
     */
    public function getTimeMid($asHex = true)
    {
        $timeMid = $this->uuid->slice(4, 2)->toInt16();
        if($asHex) return Uuid\Util::paddedDecHex($timeMid, 4);

        return $timeMid;
    }

    /**
     * Gets the time_hi_and_version field value as defined in RFC.
     *
     * @param bool $asHex Hex representation.
     * @return int|string
     */
    public function getTimeHighAndVersion($asHex = true)
    {
        $timeHighAndVersion = $this->uuid->slice(6, 2)->toInt16();
        if($asHex) return Uuid\Util::paddedDecHex($timeHighAndVersion, 4);

        return $timeHighAndVersion;
    }

    /**
     * Gets the clock_seq_hi_and_reserved field value as defined in RFC.
     *
     * @param bool $asHex Hex representation.
     * @return int|string
     */

    public function getClockSeqHighAndReserved($asHex = true)
    {
        return $this->uuid->getAt(8, $asHex);
    }

    /**
     * Gets the clock_seq_low field value as defined in RFC.
     *
     * @param bool $asHex Hex representation.
     * @return int|string
     */
    public function getClockSeqLow($asHex = true)
    {
        return $this->uuid->getAt(9, $asHex);
    }

    /**
     * Gets the nodes as byteList as defined in RFC.
     *
     * @return ByteList
     */
    public function getNodes()
    {
        return $this->uuid->slice(10, 6);
    }

    /**
     * Returns an array of all exposed data.
     *
     * @return array
     */
    public function data()
    {
        return [
            'variant' => [
                'description' => $this->describeVariant($this->getVariant()),
                'value' => $this->getVariant()
            ],
            'version' => $this->getVersion(),
            'time_low' => [
                'hex' => $this->getTimeLow(),
                'int' => $this->getTimeLow(false)
            ],
            'time_mid' => [
                'hex' => $this->getTimeMid(),
                'int' => $this->getTimeMid(false)
            ],
            'time_hi_and_version' => [
                'hex' => $this->getTimeHighAndVersion(),
                'int' => $this->getTimeHighAndVersion(false)
            ],
            'clock_seq_hi_and_reserved' => [
                'hex' => $this->getClockSeqHighAndReserved(),
                'int' => $this->getClockSeqHighAndReserved(false)
            ],
            'clock_seq_low' => [
                'hex' => $this->getClockSeqLow(),
                'int' => $this->getClockSeqLow(false)
            ],
            'nodes' => [
                'hex' => $this->getNodes()->toArray(true),
                'int' => $this->getNodes()->toArray()
            ]
        ];
    }

}