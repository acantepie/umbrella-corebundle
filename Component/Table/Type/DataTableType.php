<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 18/05/17
 * Time: 18:43.
 */

namespace Umbrella\CoreBundle\Component\Table\Type;

use Umbrella\CoreBundle\Component\Table\Model\DataTable;

/**
 * Class DataTableType.
 */
class DataTableType extends TableType
{
    /**
     * @return string
     */
    public function componentClass()
    {
        return DataTable::class;
    }
}
