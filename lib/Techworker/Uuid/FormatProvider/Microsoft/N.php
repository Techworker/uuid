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
use Techworker\Uuid\FormatProvider\Common;
use Techworker\Uuid\FormatProvider\FormatProviderInterface;

/**
 * This FormatProvider returns the format xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx.
 * (without hyphens).
 *
 * @package    Techworker\Uuid
 * @subpackage FormatProvider
 * @author     Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @copyright  2014 Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @license    MIT
 * @link       http://www.techworker.de/
 * @see        http://msdn.microsoft.com/en-us/library/97af8hh4(v=vs.110).aspx#remarksToggle
 */
class N extends Common implements FormatProviderInterface
{
    /**
     * Returns the sprintf'able format: xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx.
     *
     * @return string
     */
    public function getFormat()
    {
        return str_replace("-", "", parent::getFormat());
    }
}