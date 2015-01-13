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
 * This class exposes the creation time from a v1 uuid.
 *
 * @package    Techworker\Uuid
 * @subpackage Exposer
 * @author     Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @copyright  2014 Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @license    MIT
 * @link       http://www.techworker.de/
 */
class TimeBased extends AbstractExposer implements ExposerInterface
{
    public function key()
    {
        return 'time_based';
    }

    public function data()
    {
        $tl = $this->getComposite()->getExposer('core')->getTimeLow(false);
        $tmh = $this->getComposite()->getExposer('core')->getTimeMid(false);
        $tmh |= ($this->getUuid()->getAt(6) & 0xf) << 24;
        $tmh |= ($this->getUuid()->getAt(7) & 0xff) << 16;

        $msec = ((\Techworker\Uuid\Util::unsignedRightShift($tl, 1)) * 2 +
                (($tl & 0x7fffffff) % 2)) / 10000;
        $msec += ((\Techworker\Uuid\Util::unsignedRightShift($tmh, 1 )) * 2 +
                (($tmh & 0x7fffffff) % 2)) * 0x100000000 / 10000;
        $msec -= 12219292800000;

        return [
            'created_at' => (new \DateTime())->setTimestamp($msec / 1000)
        ];
    }
}