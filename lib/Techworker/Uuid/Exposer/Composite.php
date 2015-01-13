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
 * This class is a composite of multiple exposers for one Uuid.
 *
 * @package    Techworker\Uuid
 * @subpackage Exposer
 * @author     Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @copyright  2014 Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @license    MIT
 * @link       http://www.techworker.de/
 */
class Composite extends AbstractExposer implements ExposerInterface
{
    /**
     * The list of exposers.
     *
     * @var AbstractExposer[]
     */
    protected $exposers;

    /**
     * Constructor.
     *
     * Creates a new instance of the Composite class.
     *
     * @param Uuid $uuid The uuid.
     * @param AbstractExposer $exposers One or more exposers as variadic param.
     */
    public function __construct(Uuid $uuid = null, AbstractExposer/*...*/$exposers = null)
    {
        parent::__construct($uuid);

        // add the core exposer
        $this->addExposer(new Core($uuid));

        // check if one or more exposers are given
        if(!is_null($exposers))
        {
            foreach(array_slice(func_get_args(), 1) as $exposer)
            {
                /* @var $exposer AbstractExposer */
                $this->addExposer($exposer);
            }
        }
    }

    /**
     * Adds an exposer to the list of exposers.
     *
     * @param AbstractExposer $exposer The exposer to add.
     * @return Composite
     */
    public function addExposer(AbstractExposer $exposer)
    {
        // TODO: throw exception, no duplicates
        $exposer->setComposite($this);
        $this->exposers[$exposer->key()] = $exposer;

        return $this;
    }

    /**
     * Gets a specific exposer defined by its key.
     *
     * @param string $key The key of the exposer to retrieve.
     * @return AbstractExposer
     */
    public function getExposer($key)
    {
        // TODO: throw exception, doe not exist
        return $this->exposers[$key];
    }

    /**
     * Calls the data method of the exposer identified by the given key and
     * returns the data.
     *
     * @param string $key The name of the exposer
     * @return array
     */
    public function getExposerData($key)
    {
        // TODO: think about caching?
        return $this->exposers[$key]->data();
    }

    /**
     * Gets "composite".
     *
     * @return string
     */
    public function key()
    {
        return "composite";
    }

    /**
     * Returns a merged array of all exposers data.
     *
     * @return array
     */
    public function data()
    {
        // todo: think about cache..
        $data = array();
        foreach($this->exposers as $exposer) {
            $data[$exposer->key()] = $exposer->data($this->uuid);
        }

        return $data;
    }
}