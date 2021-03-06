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
use Techworker\Uuid\ByteProvider\Hash\Md5;
use Techworker\Uuid\Exposer\Hash;
use Techworker\Uuid\Exposer\Composite;
use Techworker\Uuid\VersionProvider\VersionProviderInterface;

/**
 * This class can be used to create or expose a RFC4122 V3 UUID.
 *
 * @package    Techworker\Uuid
 * @subpackage VersionProvider
 * @author     Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @copyright  2014 Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @license    MIT
 * @link       http://www.techworker.de/
 */
class V3 implements VersionProviderInterface
{
    /**
     * The value to hash.
     *
     * @var string
     */
    protected $value;

    /**
     * The namespace for the hash.
     *
     * @var Uuid
     */
    protected $namespace;

    /**
     * Constructor.
     *
     * Creates a new instance of the V3 class.
     *
     * @param mixed $value The value to use.
     * @param string|Uuid $namespace The namespace.
     */
    public function __construct($value, $namespace)
    {
        $this->value = $value;
        $this->namespace = ($namespace instanceof Uuid) ?
            $namespace : Uuid::factory($namespace);
    }

    /**
     * Creates a new Uuid V3 instance as defined in RFC4122.
     *
     * @return Uuid
     */
    public function create()
    {
        $md5 = new Md5($this->value, $this->namespace);
        $uuid = new Uuid($md5->provide());
        $uuid->setVersion(3);
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
        if($core->getVersion() !== 3) {
            throw new Exception("The given UUID is not a RFC4122 UUID V3.");
        }

        $composite->addExposer(new Hash());
        return $composite;
    }
}