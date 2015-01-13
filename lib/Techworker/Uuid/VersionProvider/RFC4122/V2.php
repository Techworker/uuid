<?php
/**
 * This file is part of the Techworker\Uuid package.
 *
 * (c) Benjamin Ansbach <benjaminansbach@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Techworker\Uuid\VersionProvider\RFC4122;

use Techworker\Uuid;
use Techworker\Uuid\Exception;
use Techworker\Uuid\VersionProvider\VersionProviderInterface;
use Techworker\Uuid\ByteProvider\String\MacAddress;
use Techworker\Uuid\ByteProvider\TimeAndUidBased;
use Techworker\Uuid\Exposer\Composite;

/**
 * This class can be used to create or expose a RFC4122 V2 UUID.
 *
 * @package    Techworker\Uuid
 * @subpackage VersionProvider
 * @author     Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @copyright  2014 Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @license    MIT
 * @link       http://www.techworker.de/
 */
class V2 implements VersionProviderInterface
{
    /**
     * The mac address to use.
     *
     * @var string
     */
    protected $macAddress;

    /**
     * The uid.
     *
     * @var int
     */
    protected $uid;

    /**
     * Constructor.
     *
     * Creates a new instance of the V2 class.
     *
     * @param string $macAddress The macaddress to use.
     * @param int $uid The user id.
     */
    public function __construct($macAddress, $uid)
    {
        $this->macAddress = $macAddress;
        $this->uid = $uid;
    }

    /**
     * Creates a new Uuid V2 instance as defined in RFC4122.
     *
     * @return Uuid
     */
    public function create()
    {
        $nodes = (new MacAddress($this->macAddress))->provide();
        $timestamp = (new TimeAndUidBased($this->uid))->provide();

        $uuid = new Uuid((new Uuid\ByteList(16))->append($timestamp, $nodes));
        $uuid->setVersion(2);
        $uuid->setVariant(Uuid::VARIANT_RFC_4122);
        return $uuid;

    }

    /**
     * Gets an Composite Exposer or fails with an exception.
     *
     * @param Uuid $uuid The Uuid to expose.
     * @return Composite
     * @throws Exception
     */
    public static function expose(Uuid $uuid)
    {
        $composite = new Composite($uuid);

        /* @var $core \Techworker\Uuid\Exposer\Core */
        $core = $composite->getExposer('core');
        if($core->getVersion() !== 2) {
            throw new Exception("The given UUID is not a RFC4122 UUID V2.");
        }

        $composite->addExposer(new Uuid\Exposer\TimeBased());
        return $composite;
    }

}