<?php
/**
 * This file is part of the Techworker\Uuid package.
 *
 * (c) Benjamin Ansbach <benjaminansbach@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Techworker\Uuid\FormatProvider;

use Techworker\Uuid;

/**
 * This interface defines the api to create a UUID string representation.
 *
 * @package    Techworker\Uuid
 * @author     Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @copyright  2014 Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @license    MIT
 * @link       http://www.techworker.de/
 * @see        http://www.ietf.org/rfc/rfc4122.txt
 */
interface FormatProviderInterface
{
    /**
     * Gets a sprintf'able format.
     *
     * @return mixed
     */
    public function getFormat();

    /**
     * Prepares the data from the given UUID to match sprintf format from the
     * getFormat method.
     *
     * @param Uuid $uuid The uuid.
     * @return mixed
     */
    public function prepare(Uuid $uuid);
}