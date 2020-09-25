<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 02/04/20
 * Time: 00:57
 */

namespace Umbrella\CoreBundle\Component\DataTable\Source\Modifier;

use Doctrine\ORM\QueryBuilder;

/**
 * Class EntitySearchModifier
 */
class EntitySearchModifier extends EntitySourceModifier
{
    /**
     * @inheritDoc
     */
    public function modifyQb(QueryBuilder $qb, array $queryData)
    {
        if (isset($queryData['form']['search'])) {
            $qb->andWhere('lower(e.search) LIKE :search');
            $qb->setParameter('search', '%' . strtolower($queryData['form']['search']) . '%');
        }
    }
}
