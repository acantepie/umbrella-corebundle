<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 22/03/20
 * Time: 18:45
 */

namespace Umbrella\CoreBundle\Component\DataTable\Source;

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
     * @param $dataClass
     * @param  array                   $columns
     * @param  array                   $query
     * @return \JsonSerializable|array
     */
    abstract public function search($dataClass, array $columns, array $query);

    /**
     * @param array $args
     */
    protected function resolveModifier(array $args)
    {
        uasort($this->modifiers, function (AbstractSourceModifier $a, AbstractSourceModifier $b) {
            return $a->compare($b);
        });

        foreach ($this->modifiers as $modifier) {
            $modifier->modify($args);
        }
    }
}
