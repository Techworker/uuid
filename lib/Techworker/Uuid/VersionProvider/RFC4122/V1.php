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
use Techworker\Uuid\ByteProvider\TimeBased;
use Techworker\Uuid\Exposer\Composite;

/**
 * This class can be used to create or expose a RFC4122 V1 UUID.
 *
 * @package    Techworker\Uuid
 * @subpackage VersionProvider
 * @author     Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @copyright  2014 Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @license    MIT
 * @link       http://www.techworker.de/
 */
class V1 implements VersionProviderInterface
{
    /**
     * Constructor
     *
     * Creates a new instance of the V1 class.
     *
     * @param string $macAddress The mac address that is used to create the
     *  UUID.
     */
    public function __construct($macAddress)
    {
        $this->macAddress = $macAddress;
    }

    /**
     * Creates a new Uuid V1 instance as defined in RFC4122.
     *
     * @return Uuid
     */
    public function create()
    {
        // create nodes from mac
        $nodes = (new MacAddress($this->macAddress))->provide();

        // create timestamp
        $timestamp = (new TimeBased())->provide();

        // create uuid with data and mark it
        $uuid = new Uuid((new Uuid\ByteList(16))->append($timestamp, $nodes));
        $uuid->setVersion(1);
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
        if($core->getVersion() <> 1) {
            throw new Exception("The given UUID is not a RFC4122 UUID V1.");
        }

        $composite->addExposer(new Uuid\Exposer\TimeBased($uuid));
        $composite->addExposer(new Uuid\Exposer\MacAddress($uuid));
        return $composite;
    }
}