<?php
/**
 * This file is part of the Techworker\Uuid package.
 *
 * (c) Benjamin Ansbach <benjaminansbach@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Techworker\Uuid\FormatProvider\Microsoft;

use Techworker\Uuid;
use Techworker\Uuid\FormatProvider\FormatProviderInterface;
use Techworker\Uuid\FormatProvider\Common;

/**
 * This FormatProvider returns a formatted UUID version in the format
 * {0x00000000,0x0000,0x0000,{0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00}}
 *
 * @package    Techworker\Uuid
 * @subpackage FormatProvider
 * @author     Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @copyright  2014 Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @license    MIT
 * @link       http://www.techworker.de/
 * @see        http://msdn.microsoft.com/en-us/library/97af8hh4(v=vs.110).aspx#remarksToggle
 */
class X extends Common implements FormatProviderInterface
{
    /**
     * Returns a sprintf'able format {0x00000000,0x0000,0x0000,{0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00}}.
     *
     * @return string
     */
    public function getFormat()
    {
        return "0x%08x,0x%04x,0x%04x,{0x%02x,0x%02x,0x%02x,0x%02x,0x%02x,0x%02x,0x%02x,0x%02x}}";
    }
}