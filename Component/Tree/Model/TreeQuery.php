<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 03/06/17
 * Time: 14:58
 */

namespace Umbrella\CoreBundle\Component\Tree\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;

/**
 * Class TreeQueryBuilder
 */
class TreeQuery
{
    /**
     * @var QueryBuilder
     */
    protected $qb;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var string
     */
    protected $entityAlias;

    /**
     * TreeQuery constructor.
     * @param EntityManager $em
     * @param string $entityAlias
     */
    public function __construct(EntityManager $em, $entityAlias = 'e')
    {
        $this->em = $em;
        $this->qb = $em->createQueryBuilder();
        $this->entityAlias = $entityAlias;
    }

    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->qb;
    }

    /**
     * @param Tree $tree
     */
    public function build(Tree $tree)
    {
        $this->qb->select($this->entityAlias);

        $this->qb->from($tree->entityName, $this->entityAlias);
        $this->qb->addSelect('children');
        $this->qb->leftJoin($this->entityAlias . '.children', 'children');

        // get root node
        if ($tree->entityRootAlias === null) {
            $this->qb->andWhere($this->entityAlias . '.parent IS NULL');
        } else {
            $this->qb->andWhere($this->entityAlias . '.rootAlias = :alias');
            $this->qb->setParameter('alias', $tree->entityRootAlias);
        }

        if ($tree->queryClosure) {
            call_user_func($tree->queryClosure, $this->qb);
        }
    }

    /**
     * @return array
     */
    public function getResult()
    {
        return $this->qb->getQuery()->getOneOrNullResult();
    }

    /**
     *
     */
    public function getResultDebug()
    {
        dump($this->qb->getQuery()->getSQL());
        dump($this->qb->getQuery()->getParameters());

        dump($this->qb->getQuery()->getOneOrNullResult());
        die();
    }
}