<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 27/03/20
 * Time: 22:44
 */

namespace Umbrella\CoreBundle\Component\DataTable\Source\Modifier;

/**
 * Class AbstractSourceModifier
 */
abstract class AbstractSourceModifier
{
    /**
     * @var int
     */
    private $priority;

    /**
     * SourceModifier constructor.
     * @param int $priority
     */
    public function __construct($priority = 0)
    {
        $this->priority = $priority;
    }

    /**
     * @param  array $args
     * @return mixed
     */
    abstract public function modify(array $args);

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param  AbstractSourceModifier $modifier
     * @return int
     */
    public function compare(AbstractSourceModifier $modifier)
    {
        if ($this->priority == $modifier->getPriority()) {
            return 0;
        }
        return ($this->priority < $modifier->getPriority()) ? -1 : 1;
    }
}
