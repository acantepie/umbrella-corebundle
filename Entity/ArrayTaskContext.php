<?php

namespace Umbrella\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Umbrella\CoreBundle\Component\Schedule\Context\AbstractTaskContext;

/**
 * Class JsonRuntimeEnvironment
 *
 * @ORM\Entity
 * @ORM\Table(name="umbrella_array_context")
 */
class ArrayTaskContext extends AbstractTaskContext implements \ArrayAccess, \Countable, \IteratorAggregate
{
    /**
     * @var array
     *
     * @ORM\Column(type="json", nullable=true)
     */
    private $vars = [];

    /**
     * ArrayTaskContext constructor.
     *
     * @param array $vars
     */
    public function __construct(array $vars = [])
    {
        $this->vars = $vars;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->vars);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->vars[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->vars[$offset];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->vars[$offset] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        unset($this->vars[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->vars);
    }
}
