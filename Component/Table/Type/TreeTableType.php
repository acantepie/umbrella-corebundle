<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 18/04/20
 * Time: 23:38
 */

namespace Umbrella\CoreBundle\Component\Table\Type;

use Umbrella\CoreBundle\Component\Table\Model\TreeTable;

/**
 * Class TreeTableType
 */
class TreeTableType extends TableType
{
    /**
     * @return string
     */
    public function componentClass()
    {
        return TreeTable::class;
    }
}