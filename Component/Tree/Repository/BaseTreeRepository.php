<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 09/02/18
 * Time: 19:21
 */

namespace Umbrella\CoreBundle\Component\Tree\Repository;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use Umbrella\CoreBundle\Component\Tree\Entity\BaseTreeEntity;

/**
 * Class BaseTreeRepository
 */
class BaseTreeRepository extends NestedTreeRepository
{
    /**
     * @param $rootAlias
     * @return BaseTreeEntity
     */
    public function findRoot($rootAlias = null)
    {
        $qb = $this->createQueryBuilder('e');
        $qb->where('e.parent IS NULL');
        $qb->setMaxResults(1);

        if ($rootAlias !== null) {
            $qb->where('e.rootAlias = :rootAlias');
            $qb->setParameter('rootAlias', $rootAlias);
        }

        return $qb->getQuery()->getOneOrNullResult();
    }
}