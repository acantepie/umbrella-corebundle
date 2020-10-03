<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 22/03/20
 * Time: 18:45
 */

namespace Umbrella\CoreBundle\Component\DataTable\Source;

use Umbrella\CoreBundle\Component\DataTable\Model\DataTableResult;
use Umbrella\CoreBundle\Component\DataTable\Source\Modifier\AbstractSourceModifier;

/**
 * Class AbstractDataTableSource
 */
abstract class AbstractTableSource
{
    /**
     * @var AbstractSourceModifier[]
     */
    protected $modifiers = [];

    /**
     * @param AbstractSourceModifier[] $modifiers
     */
    public function setModifiers(array $modifiers)
    {
        $this->modifiers = $modifiers;
    }

    /**
     * @param AbstractSourceModifier $modifier
     */
    public function addModifier(AbstractSourceModifier $modifier)
    {
        $this->modifiers[] = $modifier;
    }

    /**
     * @param $dataClass
     * @param  array           $columns
     * @param  array           $query
     * @return DataTableResult
     */
    abstract public function search($dataClass, array $columns, array $query) : DataTableResult;

    /**
     * @param mixed ...$args
     */
    protected function resolveModifier(...$args)
    {
        uasort($this->modifiers, function (AbstractSourceModifier $a, AbstractSourceModifier $b) {
            return $a->compare($b);
        });

        foreach ($this->modifiers as $modifier) {
            $modifier->modify(...$args);
        }
    }
}
