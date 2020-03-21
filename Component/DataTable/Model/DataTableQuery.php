<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 14/05/17
 * Time: 18:10.
 */

namespace Umbrella\CoreBundle\Component\DataTable\Model;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Umbrella\CoreBundle\Utils\ArrayUtils;

/**
 * Class DataTableQuery.
 */
class DataTableQuery implements DataTableQueryInterface
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
     * DataTableQuery constructor.
     * @param EntityManagerInterface $em
     * @param string $entityAlias
     */
    public function __construct(EntityManagerInterface $em, $entityAlias = 'e')
    {
        $this->em = $em;
        $this->entityAlias = $entityAlias;
    }


    /**
     * Build query.
     *
     * @param DataTable $table
     */
    public function build(DataTable $table)
    {
        $this->qb = $this->em->createQueryBuilder();
        $this->qb->select($this->entityAlias);
        $this->qb->from($table->entityName, $this->entityAlias);

        if ($table->queryClosure) {
            call_user_func($table->queryClosure, $this->qb);
        }
    }
    /**
     * @param Request $request
     * @param DataTable $table
     */
    public function handleRequest(Request $request, DataTable $table)
    {
        $data = $request->query->all();

        // pagination
        if ($table->paging) {
            $start = ArrayUtils::get($data, 'start', 0);
            $length = ArrayUtils::get($data, 'length');

            $this->qb->setFirstResult($start);
            if ($length !== null) {
                $this->qb->setMaxResults($length);
            }
        }

        // toolbar
        if ($table->toolbar !== null) {
            $table->toolbar->handleRequest($request);

            if ($table->toolbar->queryClosure) {
                call_user_func($table->toolbar->queryClosure, $this->qb, $table->toolbar->form->getData());
            }
        }
        // order by
        $orders = ArrayUtils::get($data,'order', array());
        foreach ($orders as $order) {
            if (!isset($order['column']) || !isset($order['dir'])) {
                continue; // request valid ?
            }

            $idx = $order['column'];
            $dir = $order['dir'];

            if (!isset($table->columns[$idx])) {
                continue; // column exist ?
            }

            /** @var Column $column */
            $column = $table->columns[$idx];

            foreach ($column->orderBy as $path) {

                // if path is not a sub property path, prefix it by alias
                if (false === strpos($path, '.')) {
                    $path = sprintf('%s.%s', $this->entityAlias, $path);
                }

                $this->qb->addOrderBy($path, strtoupper($dir));
            }
        }
    }

    /**
     * @return array
     */
    public function getResults()
    {
        $result = new Paginator($this->qb);
        return array(
            'data' => $result,
            'recordsTotal' => count($result),
            'recordsFiltered' => count($result)
        );
    }

    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->qb;
    }

    /**
     * @return array|Paginator
     */
    public function getResultsDebug()
    {
        dump($this->qb->getQuery()->getSQL());
        dump($this->qb->getQuery()->getParameters());


        // using paginator
        $result = new Paginator($this->qb, false);
        $last = null;
        foreach ($result as $row) {
            $last = $row;
        }
        if ($last)
            dump($last->id);

        // using doctrine
        $result = $this->qb->getQuery()->getResult();
        $last = null;
        foreach ($result as $row) {
            $last = $row;
        }
        if ($last)
            dump($last->id);
        die();
    }
}
