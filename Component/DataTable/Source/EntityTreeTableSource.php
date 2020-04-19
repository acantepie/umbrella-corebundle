<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 22/03/20
 * Time: 18:45
 */

namespace Umbrella\CoreBundle\Component\DataTable\Source;

use Doctrine\ORM\EntityManagerInterface;
use Umbrella\CoreBundle\Component\DataTable\Model\DataTableResult;

/**
 * Class EntityTreeTableSource
 */
class EntityTreeTableSource extends AbstractTableSource
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * DataTableEntitySource constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @inheritdoc
     */
    public function search($dataClass, array $columns, array $query)
    {
        $qb = $this->em->createQueryBuilder()
            ->select('e')
            ->from($dataClass, 'e')
            ->addOrderBy('e.lft', 'ASC');

        $this->resolveModifier(['qb' => $qb, 'query' => $query]);


        $result = new DataTableResult();
        $result->data = $qb->getQuery()->getResult();
        $result->count = count($result->data);
        return $result;

    }
}