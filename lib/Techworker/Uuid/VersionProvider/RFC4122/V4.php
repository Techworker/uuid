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
use Techworker\Uuid\Exposer\Composite;

/**
 * This class can be used to create or expose a RFC4122 V4 UUID.
 *
 * @package    Techworker\Uuid
 * @subpackage VersionProvider
 * @author     Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @copyright  2014 Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @license    MIT
 * @link       http://www.techworker.de/
 */
class V4 implements VersionProviderInterface
{
    /**
     * Creates a new Uuid V4 instance as defined in RFC4122.
     *
     * @return Uuid
     */
    public function create()
    {
        $rnd = new Uuid\ByteProvider\Random\OpenSSLPseudo();
        $uuid = new Uuid($rnd->provide());
        $uuid->setVersion(4);
        $uuid->setVariant(Uuid::VARIANT_RFC_4122);
        return $uuid;
    }

    /**
     * Gets an Composite Exposer.
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
        if($core->getVersion() !== 4) {
            throw new Exception("The given UUID is not a RFC4122 UUID V4.");
        }

        return $composite;
    }

}