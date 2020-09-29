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
use Umbrella\CoreBundle\Entity\BaseTask;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

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
     * @var ParameterBagInterface
     */
    private $parameterBag;

    /**
     * TaskManager constructor.
     * @param EntityManagerInterface $em
     * @param ParameterBagInterface  $parameterBag
     */
    public function __construct(EntityManagerInterface $em, ParameterBagInterface $parameterBag)
    {
        $this->em = $em;
        $this->parameterBag = $parameterBag;
    }

    /**
     * @param $id
     * @return null|BaseTask
     */
    public function getTask($id)
    {
        return $this->em->find($this->entityClass(), $id);
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
     * @return BaseTask[]
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
        $qb = $this->em->createQueryBuilder();
        $qb->select('e');
        $qb->from($this->entityClass(), 'e');

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
     * @param  array      $states
     * @return BaseTask[]
     */
    public function findByStates(array $states)
    {
        $criteria = new SearchTaskCriteria();
        $criteria->states = $states;
        return $this->search($criteria);
    }

    /**
     * @param  BaseTask $task
     * @return BaseTask
     */
    public function register(BaseTask $task)
    {
        if (!$task->canSchedule()) {
            throw new \LogicException(sprintf("Task %s can't be scheduled", $task));
        }
        $task->scheduled();
        $this->em->persist($task);
        $this->update($task);
        return $task;
    }

    /**
     * @param  BaseTask $task
     * @return BaseTask
     */
    public function cancel(BaseTask $task)
    {
        if (!$task->canCancel()) {
            throw new \LogicException(sprintf("Task %s can't be canceled", $task));
        }
        $task->canceled();
        $this->update($task);
        return $task;
    }

    /**
     * @param BaseTask $task
     */
    public function update(BaseTask $task)
    {
        $this->em->flush($task);
    }

    /**
     * @return BaseTask[]
     */
    public function getTasksToSchedule()
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('e');
        $qb->from($this->entityClass(), 'e');
        $qb->where('e.state = :state');
        $qb->setParameter('state', BaseTask::STATE_PENDING);
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
        $criteria->states = [BaseTask::STATE_PENDING, BaseTask::STATE_NEW, BaseTask::STATE_PENDING];
        $criteria->handlerAlias = $handlerAlias;
        return $this->countSearch($criteria) > 0;
    }

    /**
     * @return string
     */
    public function entityClass()
    {
        return $this->parameterBag->get('umbrella_core.task.class');
    }
}
