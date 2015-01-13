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

/**
 * This class exposes the mac from which the UUID was generated.
 *
 * @package    Techworker\Uuid
 * @subpackage Exposer
 * @author     Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @copyright  2014 Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @license    MIT
 * @link       http://www.techworker.de/
 */
class MacAddress extends AbstractExposer implements ExposerInterface
{
    public function key()
    {
        return 'mac_address';
    }

    public function data()
    {
        return [
            'mac' => $this->getComposite()->getExposer('core')->getNodes()->toArray(true)
        ];
    }
}