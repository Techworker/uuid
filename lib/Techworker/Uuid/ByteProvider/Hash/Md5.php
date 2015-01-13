<?php
/**
 * This file is part of the Techworker\Uuid package.
 *
 * (c) Benjamin Ansbach <benjaminansbach@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Techworker\Uuid\ByteProvider\Hash;

use Techworker\Uuid;
use Techworker\Uuid\ByteProvider\ByteProviderInterface;
use Techworker\Uuid\ByteProvider\String\Hex;
use Techworker\Uuid\Exception\ByteProvider\HashException;

/**
 * This provider creates a 16 Byte sized ByteList from the given namespace and
 * value by calculating the md5 hash of these values.
 *
 * @package    Techworker\Uuid
 * @subpackage ByteProvider
 * @author     Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @copyright  2014 Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @license    MIT
 * @link       http://www.techworker.de/
 * @see        http://www.ietf.org/rfc/rfc4122.txt
 */
class Md5 implements ByteProviderInterface
{
    /**
     * The value part to create the md5 from.
     *
     * @var string
     */
    protected $value;

    /**
     * The namespace to create the md5 from.
     *
     * @var \Techworker\Uuid
     */
    protected $namespace;

    /**
     * Constructor.
     *
     * Creates a new instance of the Techworker\Uuid\ByteProvider\Hash\Md5 class.
     *
     * @param \Closure|object|int|string $value The value.
     * @param Uuid $namespace The namespace.
     * @throws HashException
     */
    public function __construct($value, Uuid $namespace = null)
    {
        // save namespace and value
        $this->namespace = $namespace;

        // try to create a string from the value.
        if(is_callable($value)) {
            $this->value = $value();
        } elseif(is_object($value)) {
            // TODO: try - catch?
            $this->value = spl_object_hash($value);
        } elseif(is_int($value)) {
            $this->value = strval($value);
        } else {
            $this->value = $value;
        }

        // check if the conversion was successful
        if(!is_string($this->value)) {
            throw new HashException("md5");
        }
    }

    /**
     * Returns a 16 Byte sized ByteList from the md5 hash.
     *
     * @return Uuid\ByteList
     */
    public function provide()
    {
        return (new Hex(
            md5($this->namespace->toByteString() . $this->value))
        )->provide();
    }
}