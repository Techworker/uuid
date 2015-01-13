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
use Techworker\Uuid\Exposer;

/**
 * This FormatProvider returns a typically formatted UUID version in the format
 * xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx.
 *
 * @package    Techworker\Uuid
 * @subpackage FormatProvider
 * @author     Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @copyright  2014 Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @license    MIT
 * @link       http://www.techworker.de/
 * @see        http://www.ietf.org/rfc/rfc4122.txt
 */
class Common implements FormatProviderInterface
{
    /**
     * Returns a sprintf'able format.
     *
     * @return string
     */
    public function getFormat()
    {
        return "%08x-%04x-%04x-%02x%02x-%02x%02x%02x%02x%02x%02x";
    }

    /**
     * Uses the Exposer to fetch all important parts from the Uuid in the
     * sequence that can be used by the return value of the getFormat method.
     *
     * @param Uuid $uuid The UUID.
     * @return array
     */
    public function prepare(Uuid $uuid)
    {
        $info = (new Uuid\Exposer\Composite($uuid))->getExposer('core')->data();
        $data = [
            $info['time_low']['int'],
            $info['time_mid']['int'],
            $info['time_hi_and_version']['int'],
            $info['clock_seq_hi_and_reserved']['int'],
            $info['clock_seq_low']['int']
        ];

        return array_merge($data, $info['nodes']['int']);
    }
}