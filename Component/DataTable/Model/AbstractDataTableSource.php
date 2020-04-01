<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 22/03/20
 * Time: 18:45
 */

namespace Umbrella\CoreBundle\Component\DataTable\Model;

use Umbrella\CoreBundle\Component\Source\AbstractSourceModifier;
use Umbrella\CoreBundle\Component\Source\CallbackSourceModifier;

/**
 * Class AbstractDataTableSource
 */
abstract class AbstractDataTableSource
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
     * @param array $columns
     * @param array $query
     * @return DataTableResult
     */
    public abstract function search(array $columns, array $query);



}