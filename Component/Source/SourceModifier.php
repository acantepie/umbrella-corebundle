<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 27/03/20
 * Time: 22:44
 */

namespace Umbrella\CoreBundle\Component\Source;

/**
 * Class SourceModifier
 */
class SourceModifier
{
    /**
     * @var callable
     */
    private $callback;

    /**
     * @var int
     */
    private $priority;

    /**
     * SourceModifier constructor.
     * @param callable $callback
     * @param int $priority
     */
    public function __construct(callable $callback, $priority = 0)
    {
        $this->callback = $callback;
        $this->priority = $priority;
    }

    /**
     * @return callable
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param SourceModifier $modifier
     * @return int
     */
    public function compare(SourceModifier $modifier)
    {
        if ($this->priority == $modifier->getPriority()) {
            return 0;
        }
        return ($this->priority < $modifier->getPriority()) ? -1 : 1;
    }

}