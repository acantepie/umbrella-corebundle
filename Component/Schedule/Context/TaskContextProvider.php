<?php


namespace Umbrella\CoreBundle\Component\Schedule\Context;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\MappingException;
use Umbrella\CoreBundle\Entity\Job;

/**
 * Class EnvironmentProvider
 */
class TaskContextProvider
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * TaskContextProvider constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param $id
     * @return AbstractTaskContext
     */
    public function getContextOfJob($id)
    {
        /** @var Job $job */
        $job = $this->em->find(Job::class, $id);
        if (null === $job) {
            throw new \InvalidArgumentException(sprintf('Can\'t retrieve context of unexisting job %s', $id));
        }

        return $this->getContext($job->contextId);
    }

    /**
     * @param string $id
     * @return AbstractTaskContext
     */
    public function getContext(string $id)
    {
        $parts = explode(':', $id);

        if (2 !== count($parts)) {
            throw new \InvalidArgumentException(sprintf('Can\'t retrieve context, context id "%s" is invalid', $id));
        }

        list($entityClass, $entityId) = $parts;

        try {
            $md = $this->em->getClassMetadata($entityClass);
        } catch (MappingException $e) {
            throw new \InvalidArgumentException(sprintf('Can\'t retrieve context, context id "%s" has invalid entity class "%s"', $id, $entityClass));
        }

        $context = $this->em->find($entityClass, $entityId);

        if (null === $context) {
            throw new \InvalidArgumentException(sprintf('Can\'t retrieve context, context id "%s" has invalid  entity id "%s"', $id, $entityId));
        }

        $this->em->refresh($context);
        return $context;
    }

}