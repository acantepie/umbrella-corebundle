<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 28/05/17
 * Time: 13:47.
 */

namespace Umbrella\CoreBundle\Utils;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class SQLUtils.
 */
class SQLUtils
{
    /**
     * @param  QueryBuilder $queryBuilder
     * @param  string       $select
     * @return integer
     */
    public static function count(QueryBuilder $queryBuilder, $select = 'e.id')
    {
        $countQb = clone $queryBuilder;
        $countQb->resetDQLPart('select');
        $countQb->setMaxResults(null);
        $countQb->setFirstResult(null);
        return $countQb->select("COUNT($select)")->getQuery()->getSingleScalarResult();
    }

    /**
     * @param EntityManagerInterface $em
     */
    public static function disableSQLLog(EntityManagerInterface $em)
    {
        $em->getConnection()->getConfiguration()->setSQLLogger(null);
    }
}
