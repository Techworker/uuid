<?php
/**
 * This file is part of the Techworker\Uuid package.
 *
 * (c) Benjamin Ansbach <benjaminansbach@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Techworker;

use Techworker\Uuid\ByteList;

use Techworker\Uuid\ByteProvider\ByteProviderInterface;
use Techworker\Uuid\Exception\NotEnoughBytesException;
use Techworker\Uuid\FormatProvider\FormatProviderInterface;

use Techworker\Uuid\ByteProvider\String\Hex as HexStringByteProvider;
use Techworker\Uuid\ByteProvider\Nil as NilByteProvider;

use Techworker\Uuid\FormatProvider\Common as CommonFormatProvider;

use Techworker\Uuid\Exposer;

use Techworker\Uuid\VersionProvider\RFC4122\V1;
use Techworker\Uuid\VersionProvider\RFC4122\V2;
use Techworker\Uuid\VersionProvider\RFC4122\V3;
use Techworker\Uuid\VersionProvider\RFC4122\V4;
use Techworker\Uuid\VersionProvider\RFC4122\V5;

/**
 * This class/namespace provides the functionality to create and import UUIDs
 * (Universally Unique IDentifier) as defined in RFC 4122
 * (http://www.ietf.org/rfc/rfc4122.txt) and interpreted by myself (and others).
 * It supports the versions 1, 2, 3, 4 and 5 of the specification.
 *
 * @package    Techworker\Uuid
 * @author     Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @copyright  2014 Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @license    MIT
 * @link       http://www.techworker.de/
 * @see        http://www.ietf.org/rfc/rfc4122.txt
 */
final class Uuid extends ByteList
{
    /**#@+
     * The publicly known variants of an UUID.
     *
     * @var integer
     */
    const VARIANT_NCS = 0x80;
    const VARIANT_RFC_4122 = 0x40;
    const VARIANT_MICROSOFT = 0x20;
    const VARIANT_FUTURE = 0x0;

    /**
     * Constructor
     *
     * Creates a new instance of the \Techworker\Uuid class. If you omit the
     * bytes, we will end up with an empty Nil Uuid
     * (00000000-0000-0000-0000-000000000000).
     *
     * @param ByteList $bytes The initial bytes.
     */
    public function __construct(ByteList $bytes = null)
    {
        parent::__construct(16);

        if(!is_null($bytes)) {
            $this->append($bytes);
            for($i = $bytes->count(); $i < 16; $i++) {
                $this->add(0, $i);
            }
        } else {
            $this->append((new NilByteProvider)->provide());
        }
    }

    /**
     * This method sets the variant of the current UUID.
     *
     * @param integer $variant A variant specified by the
     *  \Techworker\Uuid::VARIANT_* constants.
     * @throws \InvalidArgumentException
     * @return Uuid
     */
    public function setVariant($variant)
    {
        $variantByte = $this->getAt(8);
        switch($variant)
        {
            // set first bit from left to 0
            case self::VARIANT_NCS:
                $variantByte &= 0x7f;
                break;
            // set first two bits from left to 10
            case self::VARIANT_RFC_4122:
                $variantByte &= 0x3f;
                $variantByte |= 0x80;
                break;
            // set first three bits from left to 110
            case self::VARIANT_MICROSOFT:
                $variantByte &= 0x1f;
                $variantByte |= 0xc0;
                break;
            // set first three bits from left to 111
            case self::VARIANT_FUTURE:
                $variantByte &= 0x1f;
                $variantByte |= 0xe0;
                break;
            default:
                throw new \InvalidArgumentException("Unknown variant provided.");
                break;
        }

        $this->setAt(8, $variantByte);
        return $this;
    }

    /**
     * Sets the version of the current Uuid.
     *
     * @param int $version The version to set.
     * @return Uuid
     */
    public function setVersion($version)
    {
        $versionByte = $this->getAt(6);
        $versionByte &= 0x0f;
        $versionByte |= ($version << 4);
        $this->setAt(6, $versionByte);

        return $this;
    }

    /**
     * Tries to create a new instance of the \Techworker\Uuid class depending on
     * the given value param which could either be a string or a by array
     * containing 16 elements.
     *
     * @param null|string|array[16]|Uuid|ByteProviderInterface $value The value
     *  to create the Uuid instance from.
     *
     * @return Uuid
     * @throws \InvalidArgumentException
     */
    public static function factory($value = null)
    {
        if(is_string($value)) {
            return self::fromString($value);
        } elseif(is_array($value) && count($value) === 16) {
            return self::fromByteList(ByteList::factoryFromArray($value));
        } elseif($value instanceof Uuid) {
            return clone $value;
        } elseif($value instanceof ByteProviderInterface) {
            /* @var $value ByteProviderInterface */
            return self::fromByteList($value->provide());
        } elseif(is_null($value)) {
            return new self();
        }

        throw new \InvalidArgumentException(
            "Cannot factory a Uuid from the given value."
        );
    }


    /**
     * Shortcut to create a rfc4122 v1 UUID.
     *
     * @param string $macAddress The mac address to use.
     * @return Uuid
     */
    public static function v1($macAddress)
    {
        return (new V1($macAddress))->create();
    }

    /**
     * Shortcut to create a rfc4122 v2 UUID.
     *
     * @param string $macAddress The mac address to use.
     * @param int $uid The uid of the current user - possibly fetched by
     *  posix_getuid() which, of cource, will only work on unix based operating
     *  systems.
     * @return Uuid
     */
    public static function v2($macAddress, $uid)
    {
        return (new V2($macAddress, $uid))->create();
    }

    /**
     * Shortcut to create a rfc4122 v3 UUID.
     *
     * @param string $value The value to include in the UUID.
     * @param Uuid|string $namespace The namespace to mark the UUID.
     * @return Uuid
     */
    public static function v3($value, $namespace)
    {
        return (new V3($value, $namespace))->create();
    }

    /**
     * Shortcut to create a rfc4122 v4 UUID.
     *
     * @return Uuid
     */
    public static function v4()
    {
        return (new V4())->create();
    }

    /**
     * Shortcut to create a rfc4122 v5 UUID.
     *
     * @param string $value The value to include in the UUID.
     * @param Uuid|string $namespace The namespace to mark the UUID.
     * @return Uuid
     */
    public static function v5($value, $namespace)
    {
        return (new V5($value, $namespace))->create();
    }

    /**
     * Creates a new \Techworker\Uuid instance from the given Uuid string.
     * The values are guessed.
     *
     * @param string $uuidString The UUID in one of the
     *  \Techworker\Uuid::FORMAT_* formats.
     * @return Uuid
     * @throws \InvalidArgumentException
     */
    public static function fromString($uuidString)
    {
        if(is_null($uuidString) || strlen($uuidString) < 32)
        {
            throw new \InvalidArgumentException(
                "The given string cannot be a source for a valid UUID."
            );
        }

        return Uuid::fromByteList(
            (new HexStringByteProvider($uuidString))->provide()
        );
    }

    /**
     * Creates a new \Techworker\Uuid instance from the given ByteList.
     *
     * @param ByteList $bytes The byte array to create the UUID from.
     * @return Uuid
     *
     * @throws \InvalidArgumentException
     */
    public static function fromByteList(ByteList $bytes)
    {
        // simple length check
        if($bytes->count() !== 16)
        {
            throw new \InvalidArgumentException(
                "The ByteList should contain 16 bytes, " . $bytes->count() .
                " provided"
            );
        }

        return new Uuid($bytes);
    }

    /**
     * Checks whether the given UUID equals the current Uuid.
     *
     * @param string|ByteList|array $value The value to check the current Uuid
     *  against.
     * @return bool
     */
    public function equals($value)
    {
        // call yourself with a generated id
        if(is_string($value)) {
            return $this->equals(
                (new HexStringByteProvider($value))->provide()
            );
        }

        return parent::equals($value);
    }

    /**
     * A to toString implementation beside the PHP magic __toString method
     * which accepts a format provider.
     *
     * @param FormatProviderInterface $provider The provider that formats the
     *  UUID.
     * @return string
     * @throws NotEnoughBytesException
     */
    public function toString(FormatProviderInterface $provider)
    {
        if(!$this->isValidUuid()) {
            throw new NotEnoughBytesException($this->count());
        }

        return vsprintf($provider->getFormat(), $provider->prepare($this));
    }

    /**
     * Magic __toString implementation that calls the toString method with the
     * common UUID format provider.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString(new CommonFormatProvider());
    }

    /**
     * Clones a Uuid instance and creates a new one.
     *
     * @return Uuid
     */
    public function __clone()
    {
        return new Uuid(ByteList::factoryFromArray($this->toArray()));
    }

    /**
     * Gets a value indicating whether the current byte data is valid.
     *
     * @return bool
     */
    public function isValidUuid()
    {
        return $this->count() === 16;
    }

}

define('TW_UUID_NAMESPACE_DNS',  '6ba7b810-9dad-11d1-80b4-00c04fd430c8');
define('TW_UUID_NAMESPACE_URL',  '6ba7b811-9dad-11d1-80b4-00c04fd430c8');
define('TW_UUID_NAMESPACE_OID',  '6ba7b812-9dad-11d1-80b4-00c04fd430c8');
define('TW_UUID_NAMESPACE_X500', '6ba7b814-9dad-11d1-80b4-00c04fd430c8');