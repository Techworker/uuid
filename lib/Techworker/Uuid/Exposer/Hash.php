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

/**
 * This class exposes the hash from which the UUID was generated. Its actually
 * quite useless because the UUID itself is the hash but hey.
 *
 * @package    Techworker\Uuid
 * @subpackage Exposer
 * @author     Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @copyright  2014 Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @license    MIT
 * @link       http://www.techworker.de/
 */
class Hash extends AbstractExposer implements ExposerInterface
{
    /**
     * Gets 'hash'.
     *
     * @return string
     */
    public function key()
    {
        return 'hash';
    }

    /**
     * Gets the exposed hash.
     *
     * @return array
     */
    public function data()
    {
        return [
            'hash' => implode("", $this->uuid->toArray(true))
        ];
    }
}