<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 22/03/20
 * Time: 18:45
 */

namespace Umbrella\CoreBundle\Component\DataTable\Model;

use Umbrella\CoreBundle\Component\Source\SourceModifier;

/**
 * Class AbstractDataTableSource
 */
abstract class AbstractDataTableSource
{
    /**
     * @var SourceModifier[]
     */
    protected $modifiers = array();

    /**
     * @param array $modifiers
     */
    public function setModifiers(array $modifiers)
    {
        $this->modifiers = $modifiers;
    }

    /**
     * @param null $parameter
     * @param null $_
     */
    protected function resolveModifier($parameter = null, $_ = null)
    {
        uasort($this->modifiers, function (SourceModifier $a, SourceModifier $b) {
            return $a->compare($b);
        });

        foreach ($this->modifiers as $modifier) {
            call_user_func($modifier->getCallback(), $parameter, $_);
        }
    }

    /**
     * @param array $columns
     * @param array $query
     * @return DataTableResult
     */
    public abstract function search(array $columns, array $query);



}