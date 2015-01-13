<?php
/**
 * This file is part of the Techworker\Uuid package.
 *
 * (c) Benjamin Ansbach <benjaminansbach@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Techworker\Uuid\VersionProvider;

use Techworker\Uuid;

/**
 * This interface defines the api for a UUID version. A version combines one
 * or more ByteProvider to create an UUID.
 *
 * @package    Techworker\Uuid
 * @author     Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @copyright  2014 Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @license    MIT
 * @link       http://www.techworker.de/
 * @see        http://www.ietf.org/rfc/rfc4122.txt
 */
interface VersionProviderInterface
{
    /**
     * Returns a new \Techworker\Uuid instance of the current implementation.
     *
     * @return \Techworker\Uuid
     */
    public function create();

    /**
     * Reveals (as good as possible) the information of the current UUID and
     * returns the Composite Exposer.
     *
     * @param Uuid $uuid The UUID to expose
     * @return \Techworker\Uuid\Exposer\Composite
     */
    public static function expose(Uuid $uuid);
}