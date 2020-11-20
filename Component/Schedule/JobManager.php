<?php

namespace Umbrella\CoreBundle\Component\Schedule;

use Doctrine\ORM\EntityManagerInterface;
use Umbrella\CoreBundle\Entity\Job;

/**
 * Class JobManager
 */
class JobManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * JobManager constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param $id
     *
     * @return Job
     */
    public function getJob($id)
    {
        return $this->em->find(Job::class, $id);
    }

    /**
     * @param array $states
     *
     * @return Job[]
     */
    public function getJobsByStates(array $states = [])
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('e');
        $qb->from(Job::class, 'e');
        $qb->where('e.state IN (:states)');
        $qb->setParameter('states', $states);
        $qb->orderBy('e.updatedAt', 'DESC');

        return $qb->getQuery()->getResult();
    }

    /**
     * @return Job[]
     */
    public function getPendingJobs($ids = [])
    {
        if (!is_iterable($ids) && $ids) {
            $ids = [$ids];
        }

        $qb = $this->em->createQueryBuilder();
        $qb->select('e');
        $qb->from(Job::class, 'e');
        $qb->where('e.state = :state');
        $qb->setParameter('state', Job::STATE_PENDING);

        if (count($ids) > 0) {
            $qb->andWhere('e.id IN (:ids)');
            $qb->setParameter('ids', $ids);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @param Job $job
     */
    public function deleteJob(Job $job)
    {
        $this->em->remove($job);
        $this->em->flush();
    }

    public function deleteJobsNotRunning()
    {
        $this->em->createQueryBuilder()
            ->delete(Job::class, 'e')
            ->where('e.state != :state')
            ->setParameter('state', Job::STATE_RUNNING)
            ->getQuery()
            ->execute();
    }

    /**
     * @param Job $job
     */
    public function saveJob(Job $job)
    {
        $this->em->persist($job);
        $this->em->flush();
    }
}
