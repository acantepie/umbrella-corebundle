<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 22/03/20
 * Time: 18:45
 */

namespace Umbrella\CoreBundle\Component\DataTable\Model;

/**
 * Class AbstractDataTableSource
 */
abstract class AbstractDataTableSource
{
    /**
     * @var array
     */
    protected $modifiers = array();

    /**
     * TODO : manage priority
     *
     * @param callable $modifier
     * @param $priority
     */
    public function addModifier(callable $modifier, $priority = 0)
    {
        $this->modifiers[] = $modifier;
    }

    /**
     * @param array $columns
     * @param array $query
     * @return DataTableResult
     */
    public abstract function search(array $columns, array $query);
}