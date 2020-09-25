<?php


namespace Umbrella\CoreBundle\Component\DataTable\Source\Modifier;

use Doctrine\ORM\QueryBuilder;

/**
 * Class EntitySourceModifier
 */
abstract class EntitySourceModifier extends AbstractSourceModifier
{
    public final function modify(...$args)
    {
        $this->modifyQb($args[0], $args[1]);
    }

    abstract public function modifyQb(QueryBuilder $qb, array $queryData);
}