<?php
/**
 * This file is part of the Techworker\Uuid package.
 *
 * (c) Benjamin Ansbach <benjaminansbach@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Techworker\Uuid;

use Techworker\Uuid\Exception\ByteList\IntConversionException;
use Techworker\Uuid\Exception\ByteList\OffsetNotValidException;
use Techworker\Uuid\Exception\ByteList\MaxSizeExceededException;
use Techworker\Uuid\Exception\ByteList\NotAnUnsignedByteException;

/**
 * This collection holds a list of unsigned bytes.
 *
 * @package    Techworker\Uuid
 * @author     Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @copyright  2015 Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @license    MIT
 * @link       http://www.techworker.de/
 */

/**
 * @method bool isset(int $offset) Checks if the given offset exists.
 * @method bool unset(int $offset) Removes the given offset.
 */
class ByteList implements \Iterator
{
    /**
     * The internal container.
     *
     * @var int[]
     */
    private $list = [];

    /**
     * The maximum size of the collection.
     *
     * @var int
     */
    private $maxSize = 0;

    /**
     * The internal pointer for the \Iterator interface.
     *
     * @var int
     */
    private $position = 0;

    /**
     * Constructor.
     *
     * Creates a new instance of the \Techworker\Uuid\ByteCollection class.
     *
     * @param int $maxSize The maximum size of the collection.
     */
    public function __construct($maxSize = 0)
    {
        // set the maximum size.
        $this->maxSize = $maxSize > 0 ? $maxSize : $this->maxSize;
    }

    /**
     * Gets the number of elements in the list.
     *
     * @return int
     */
    public function count()
    {
        return count($this->list);
    }

    /**
     * A factory to create a new ByteList instance from the given data array.
     *
     * @param array $bytes The list of bytes to initially append.
     * @param bool $isHex A value indicating whether the values are encoded as
     *  hex.
     *
     * @return ByteList
     */
    public static function factoryFromArray(array $bytes, $isHex = false)
    {
        $list = new ByteList();

        foreach ($bytes as $byte) {
            $isHex ? $list->addHex($byte) : $list->add($byte);
        }

        return $list;
    }

    /**
     * Rewinds the Iterator to the first element.
     */
    function rewind()
    {
        $this->position = 0;
    }

    /**
     * Returns the current element.
     *
     * @return int
     */
    function current()
    {
        return $this->list[$this->position];
    }

    /**
     * Returns the key of the current element.
     *
     * @return mixed scalar on success, or null on failure.
     */
    function key()
    {
        return $this->position;
    }

    /**
     * Moves the pointer forward to the next element.
     */
    function next()
    {
        ++$this->position;
    }

    /**
     * Checks if current position is valid.
     *
     * @return bool
     */
    function valid()
    {
        return isset($this->list[$this->position]);
    }

    /**
     * Isset and Unset implementation since the class cannot contain functions
     * with these names.
     *
     * @param string $name The name of the function
     * @param $arguments
     * @return void|bool
     */
    public function __call($name, $arguments)
    {
        switch ($name) {
            case 'isset':
                return !is_null($this->getAt($arguments[0]));
                break;
            case 'unset':
                $this->remove($this->getAt($arguments[0]));
                return true;
                break;
        }

        return false;
    }

    /**
     * Updates the internal container with the given byte at the given
     * offset/key.
     *
     * @param mixed $byte
     * @param int $offset The offset to set.
     * @throws OffsetNotValidException
     * @throws MaxSizeExceededException
     */
    public function add($byte, $offset = null)
    {
        // check offset
        if (!is_null($offset) && !is_int($offset)) {
            throw new OffsetNotValidException($offset);
        }

        // check value
        $this->check($byte);

        // append or replace decision
        if (is_null($offset)) {
            $this->list[] = $byte;
        } else {
            $this->list[$offset] = $byte;
        }

        // check exceeded limit
        if ($this->maxSize > 0 && count($this->list) > $this->maxSize) {
            throw new MaxSizeExceededException($this->maxSize);
        }
    }

    /**
     * Updates the internal container with the given hex at the given
     * offset/key.
     *
     * @param string $hex
     * @param int $offset The offset to set.
     */
    public function addHex($hex, $offset = null)
    {
        $this->add(hexdec($hex), $offset);
    }

    /**
     * Updates the internal container with the given value at the given
     * offset/key.
     *
     * @param int $offset The offset to set.
     * @param int $byte The byte to set,
     */
    public function setAt($offset, $byte)
    {
        $this->add($byte, $offset);
    }

    /**
     * Updates the internal container with the given hex at the given
     * offset/key.
     *
     * @param int $hex
     * @param int $offset The offset to set.
     */
    public function setHexAt($offset, $hex)
    {
        $this->addHex($hex, $offset);
    }

    /**
     * Checks whether the given offset exists in the list.
     *
     * @param int $offset The offset to check.
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->list[$offset]);
    }

    /**
     * Removes the given offset from the list.
     *
     * @param int $offset
     */
    public function remove($offset)
    {
        unset($this->list[$offset]);
    }

    /**
     * Gets the byte at the given offset and returns its value or null if it
     * does not exist.
     *
     * @param int $offset
     * @param bool $asHex Value indicating whether the dechex function should
     *  be applied.
     * @return int|null
     */
    public function getAt($offset, $asHex = false)
    {
        $value = isset($this->list[$offset]) ? $this->list[$offset] : null;
        if (!is_null($value) && $asHex) {
            return Util::paddedDecHex($value, 2);
        }

        return $value;
    }

    /**
     * Internal method to check whether the given value is in the unsigned byte
     * range.
     *
     * @param int $byte The value to check.
     * @throws NotAnUnsignedByteException
     */
    private function check($byte)
    {
        if (!is_int($byte) || $byte > 255 || $byte < 0) {
            throw new NotAnUnsignedByteException($byte);
        }
    }

    /**
     * Returns a new ByteList with the values of the current list starting at
     * offsetStart and ending at offset end. The offset starts with index 0.
     *
     * @param int $offsetStart The offset to start at.
     * @param int $offsetEnd The offset to end at.
     * @return ByteList
     */
    public function range($offsetStart, $offsetEnd)
    {
        return $this->slice($offsetStart, $offsetEnd);
    }

    /**
     * Extracts the given number of values (param $length) starting at the
     * given offset ($offsetStart). If you omit the length, all values from the
     * given start offset will be returned.
     *
     * @param int $offsetStart The start offset.
     * @param int $length The number of elements to return.
     * @return ByteList
     */
    public function slice($offsetStart, $length = -1)
    {
        return ByteList::factoryFromArray(
            array_slice($this->list, $offsetStart, $length)
        );
    }

    /**
     * Appends the given ByteList(s) to the current ByteList and returns the
     * current one.
     *
     * @param ByteList|array $list The list(s) to append.
     * @return ByteList
     */
    public function append($list)
    {
        $lists = func_get_args();
        foreach ($lists as $list) {
            /* @var $list ByteList */
            foreach ($list as $byte) {
                $this->add($byte);
            }
        }

        return $this;
    }

    /**
     * Replaces all values starting at the given offset with the given values.
     *
     * @param int $offset The offset to start the replacement at.
     * @param array|ByteList $values The values to overwrite.
     * @return ByteList
     */
    public function replace($offset, $values)
    {
        if ($values instanceof ByteList) {
            $values = $values->toArray();
        }

        for ($i = $offset; $i < ($offset + count($values)); $i++) {
            $this->setAt($i, $values[$i - $offset]);
        }

        return $this;
    }

    /**
     * Gets the underlying array container which holds all values.
     *
     * @param bool $asHex A value indicating that the array will contain the
     * hex representation (2 digit) instead of the bytes.
     *
     * @return \int[]
     */
    public function toArray($asHex = false)
    {
        $return = array();
        foreach ($this as $offset => $byte) {
            $return[$offset] = $this->getAt($offset, $asHex);
        }

        return $return;
    }

    /**
     * Compares the given ByteList / Array with the current one.
     *
     * @param array|ByteList $value Value to compare with.
     * @return bool
     */
    public function equals($value)
    {
        if (is_array($value)) {
            $value = ByteList::factoryFromArray($value);
        }

        return count(array_diff($value->list, $this->list)) === 0;
    }

    /**
     * Packs the containing bytes in a 32 Bit integer value.
     *
     * @return int
     * @throws IntConversionException
     */
    public function toInt32()
    {
        if ($this->count() < 4 || $this->count() > 4) {
            throw new IntConversionException("Int32", $this->count());
        }

        return $this->toInt();
    }

    /**
     * Packs the containing bytes in a 16 Bit integer value.
     *
     * @return int
     * @throws IntConversionException
     */
    public function toInt16()
    {
        if ($this->count() < 2 || $this->count() > 2) {
            throw new IntConversionException("Int16", $this->count());
        }

        return $this->toInt();
    }

    /**
     * Packs the containing bytes in an integer value.
     *
     * @return int
     * @throws IntConversionException
     */
    public function toInt()
    {
        $result = 0;
        foreach ($this as $byte) {
            $result = $result << 8 | $byte;
        }

        return $result;
    }

    /**
     * Packs the containing bytes into a string using chr()
     *
     * @return string
     */
    public function toByteString()
    {
        return implode("", array_map('chr', $this->list));
    }
}