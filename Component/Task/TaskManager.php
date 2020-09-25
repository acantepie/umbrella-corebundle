<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 18/01/19
 * Time: 21:16
 */

namespace Umbrella\CoreBundle\Component\Task;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Umbrella\CoreBundle\Entity\UmbrellaTask;

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

    public function createTask($handlerAlias, array $parameters = [])
    {
        return new UmbrellaTask($handlerAlias, $parameters);
    }

    /**
     * @param $taskId
     * @return null|UmbrellaTask
     */
    public function getTask($taskId)
    {
        if (0 === preg_match('/^(.*)\.([0-9]+)$/', $taskId, $matches)) {
            return null;
        }

        return $this->createQueryBuilder('e')
            ->where('e.id = :id')
            ->setParameter('id', $matches[2])
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param  SearchTaskCriteria $criteria
     * @return int
     */
    public function countSearch(SearchTaskCriteria $criteria)
    {
        return $this->searchQuery($criteria)
            ->resetDQLPart('select')
            ->select('COUNT(e.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param  SearchTaskCriteria $criteria
     * @return UmbrellaTask[]
     */
    public function search(SearchTaskCriteria $criteria)
    {
        return $this->searchQuery($criteria)->getQuery()->getResult();
    }

    /**
     * @param  SearchTaskCriteria $criteria
     * @return QueryBuilder
     */
    public function searchQuery(SearchTaskCriteria $criteria)
    {
        $qb = $this->createQueryBuilder('e');

        if (!empty($criteria->states)) {
            $qb->andWhere('e.state IN (:states)');
            $qb->setParameter('states', $criteria->states);
        }

        if (!empty($criteria->handlerAlias)) {
            $qb->andWhere('e.handlerAlias = :handler_alias');
            $qb->setParameter('handler_alias', $criteria->handlerAlias);
        }

        if ($criteria->maxResults > 0) {
            $qb->setMaxResults($criteria->maxResults);
        }

        $qb->orderBy('e.createdAt', 'DESC');
        return $qb;
    }

    /**
     * @param  array          $states
     * @return UmbrellaTask[]
     */
    public function findByStates(array $states)
    {
        $criteria = new SearchTaskCriteria();
        $criteria->states = $states;
        return $this->search($criteria);
    }

    /**
     * Alias
     * @param  UmbrellaTask $task
     * @return UmbrellaTask
     */
    public function register(UmbrellaTask $task)
    {
        return $this->schedule($task);
    }

    /**
     * @param  UmbrellaTask $task
     * @return UmbrellaTask
     */
    public function schedule(UmbrellaTask $task)
    {
        if (!$task->canSchedule()) {
            throw new \LogicException(sprintf("Task %s can't be scheduled", $task->getTaskId()));
        }
        $task->scheduled();
        $this->em->persist($task);
        $this->update($task);
        return $task;
    }

    /**
     * @param  UmbrellaTask $task
     * @return UmbrellaTask
     */
    public function cancel(UmbrellaTask $task)
    {
        if (!$task->canCancel()) {
            throw new \LogicException(sprintf("Task %s can't be canceled", $task->getTaskId()));
        }
        $task->canceled();
        $this->update($task);
        return $task;
    }

    /**
     * @param UmbrellaTask $task
     */
    public function update(UmbrellaTask $task)
    {
        $this->em->flush($task);
    }

    /**
     * @return UmbrellaTask[]
     */
    public function getTasksToSchedule()
    {
        $qb = $this->createQueryBuilder('e');
        $qb->where('e.state = :state');
        $qb->setParameter('state', UmbrellaTask::STATE_PENDING);
        $qb->orderBy('e.priority', 'DESC');

        return $qb->getQuery()->getResult();
    }

    /**
     * @param $handlerAlias
     * @return bool
     */
    public function hasPendingOrRunningTasks($handlerAlias)
    {
        $criteria = new SearchTaskCriteria();
        $criteria->states = [UmbrellaTask::STATE_PENDING, UmbrellaTask::STATE_NEW, UmbrellaTask::STATE_PENDING];
        $criteria->handlerAlias = $handlerAlias;
        return $this->countSearch($criteria) > 0;
    }

    /**
     * @param $alias
     * @param  null         $indexBy
     * @return QueryBuilder
     */
    private function createQueryBuilder($alias, $indexBy = null)
    {
        return $this->em->createQueryBuilder()
            ->select($alias, $indexBy)
            ->from(UmbrellaTask::class, $alias);
    }
}
