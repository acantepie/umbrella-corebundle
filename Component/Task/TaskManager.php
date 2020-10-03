<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 18/01/19
 * Time: 21:16
 */

namespace Umbrella\CoreBundle\Component\Task;

use Doctrine\ORM\QueryBuilder;
use Umbrella\CoreBundle\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Umbrella\CoreBundle\Entity\BaseTaskConfig;

/**
 * Class TaskManager
 */
class TaskManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * TaskManager constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param $id
     * @return Task|null
     */
    public function getTask($id)
    {
        return $this->em->find(Task::class, $id);
    }

    /**
     * @param  SearchTaskCriteria $criteria
     * @return int
     */
    public function countSearch(SearchTaskCriteria $criteria)
    {
        return $this->searchQb($criteria)
            ->resetDQLPart('select')
            ->select('COUNT(e.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param  SearchTaskCriteria $criteria
     * @return Task[]
     */
    public function search(SearchTaskCriteria $criteria)
    {
        return $this->searchQb($criteria)->getQuery()->getResult();
    }

    /**
     * @param  SearchTaskCriteria $criteria
     * @return QueryBuilder
     */
    public function searchQb(SearchTaskCriteria $criteria)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('e');
        $qb->addSelect('c');
        $qb->from(Task::class, 'e');
        $qb->innerJoin('e.config', 'c');

        if (!empty($criteria->states)) {
            $qb->andWhere('e.state IN (:states)');
            $qb->setParameter('states', $criteria->states);
        }

        if (!empty($criteria->handlerAlias)) {
            $qb->andWhere('c.handlerAlias = :handler_alias');
            $qb->setParameter('handler_alias', $criteria->handlerAlias);
        }

        if (!empty($criteria->tag)) {
            $qb->andWhere('c.tag = :tag');
            $qb->setParameter('tag', $criteria->tag);
        }

        if ($criteria->maxResults > 0) {
            $qb->setMaxResults($criteria->maxResults);
        }

        $qb->orderBy('e.createdAt', 'DESC');
        return $qb;
    }

    /**
     * @param  array  $states
     * @return Task[]
     */
    public function findByStates(array $states)
    {
        $criteria = new SearchTaskCriteria();
        $criteria->states = $states;
        return $this->search($criteria);
    }

    /**
     * @param  Task $task
     * @return Task
     */
    public function register(BaseTaskConfig $config)
    {
        $task = new Task();
        $task->config = $config;
        $task->scheduled();
        $this->em->persist($task);
        $this->save($task);
        return $task;
    }

    /**
     * @param  Task $task
     * @return Task
     */
    public function cancel(Task $task)
    {
        if (!$task->canCancel()) {
            throw new \LogicException(sprintf("Task %s can't be canceled", $task));
        }
        $task->canceled();
        $this->save($task);
        return $task;
    }

    /**
     * @param Task $task
     */
    public function save(Task $task)
    {
        $this->em->flush($task);
    }

    /**
     * @return Task[]
     */
    public function getTasksToSchedule()
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('e');
        $qb->from(Task::class, 'e');
        $qb->where('e.state = :state');
        $qb->setParameter('state', Task::STATE_PENDING);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param $handlerAlias
     * @return bool
     */
    public function hasPendingOrRunningTasks($handlerAlias)
    {
        $criteria = new SearchTaskCriteria();
        $criteria->states = [Task::STATE_PENDING, Task::STATE_NEW, Task::STATE_PENDING];
        $criteria->handlerAlias = $handlerAlias;
        return $this->countSearch($criteria) > 0;
    }
}
