<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 22/03/20
 * Time: 18:45
 */

namespace Umbrella\CoreBundle\Component\DataTable\Source;

/**
 * Class AbstractDataTableSource
 */
abstract class AbstractTableSource
{
    /**
     * @var AbstractSourceModifier[]
     */
    protected $modifiers = array();

    /**
     * @param AbstractSourceModifier[] $modifiers
     */
    public function setModifiers(array $modifiers)
    {
        $this->modifiers = $modifiers;
    }

    /**
     * @param array $args
     */
    protected function resolveModifier(array $args)
    {
        uasort($this->modifiers, function (CallbackSourceModifier $a, CallbackSourceModifier $b) {
            return $a->compare($b);
        });

        foreach ($this->modifiers as $modifier) {
            $modifier->modify($args);
        }
    }

    /**
     * @param $dataClass
     * @param array $columns
     * @param array $query
     * @return \JsonSerializable|array
     */
    public abstract function search($dataClass, array $columns, array $query);



}