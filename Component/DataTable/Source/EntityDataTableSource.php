<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 22/03/20
 * Time: 18:45
 */

namespace Umbrella\CoreBundle\Component\DataTable\Source;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Umbrella\CoreBundle\Component\Column\Column;
use Umbrella\CoreBundle\Component\DataTable\Model\DataTableResult;
use Umbrella\CoreBundle\Utils\ArrayUtils;

/**
 * Class EntityDataTableSource
 */
class EntityDataTableSource extends AbstractTableSource
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * DataTableEntitySource constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function search($dataClass, array $columns, array $query): DataTableResult
    {
        $queryData = $query['query'];
        $formData = $query['form'];

        $qb = $this->em->createQueryBuilder()
            ->select('e')
            ->from($dataClass, 'e');

        $this->resolveModifier($qb, $formData);

        // pagination
        if (isset($queryData['start'])) {
            $qb->setFirstResult($queryData['start']);
        }

        if (isset($queryData['length'])) {
            $qb->setMaxResults($queryData['length']);
        }

        // order by
        $orders = ArrayUtils::get($queryData, 'order', []);
        foreach ($orders as $order) {
            if (!isset($order['column']) || !isset($order['dir'])) {
                continue; // request valid ?
            }

            $idx = $order['column'];
            $dir = $order['dir'];

            if (!isset($columns[$idx])) {
                continue; // column exist ?
            }

            /** @var Column $column */
            $column = $columns[$idx];

            foreach ($column->getOrderBy() as $path) {
                // if path is not a sub property path, prefix it by alias
                if (false === strpos($path, '.')) {
                    $path = sprintf('e.%s', $path);
                }

                $qb->addOrderBy($path, strtoupper($dir));
            }
        }

        $paginator = new Paginator($qb);

        $result = new DataTableResult();
        $result->count = count($paginator);
        $result->data = $paginator;

        return $result;
    }
}
