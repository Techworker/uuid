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
 * This is the abstract exposer which should be used for each specialized
 * exposer.
 *
 * @package    Techworker\Uuid
 * @subpackage Exposer
 * @author     Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @copyright  2014 Benjamin Ansbach <benjaminansbach@googlemail.com>
 * @license    MIT
 * @link       http://www.techworker.de/
 */
abstract class AbstractExposer implements ExposerInterface
{
    /**
     * @var
     */
    protected $composite;

    /**
     * The UUID instance to extract data from.
     *
     * @var Uuid
     */
    protected $uuid;

    /**
     * Constructor
     *
     * Creates a new instance of the derived class.
     *
     * @param Uuid $uuid The uuid to initialize the exposer with.
     */
    public function __construct(Uuid $uuid = null) {
        $this->uuid = $uuid;
    }

    /**
     * Sets the uuid.
     *
     * @param Uuid $uuid
     * @return AbstractExposer
     */
    public function setUuid(Uuid $uuid)
    {
        $this->uuid = $uuid;
        return $this;
    }

    /**
     * Gets the UUID.
     *
     * @return Uuid
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Sets the uuid.
     *
     * @param Uuid $uuid
     * @return AbstractExposer
     */
    public function setComposite(Composite $composite)
    {
        $this->composite = $composite;
        return $this;
    }

    /**
     * Gets the Composite object.
     *
     * @return Composite
     */
    public function getComposite()
    {
        return $this->composite;
    }
}