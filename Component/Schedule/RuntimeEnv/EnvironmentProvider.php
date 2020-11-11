<?php


namespace Umbrella\CoreBundle\Component\Schedule\RuntimeEnv;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\MappingException;
use Umbrella\CoreBundle\Entity\Job;

/**
 * Class EnvironmentProvider
 */
class EnvironmentProvider
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * EnvironmentProvider constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param Job $job
     */
    public function getEnvironment($id)
    {
        if (null === $id) {
            return null;
        }

        $parts = explode(':', $id);

        if (2 !== count($parts)) {
            return null;
        }

        list($entityClass, $entityId) = $parts;

        try {
            $md = $this->em->getClassMetadata($entityClass);
        } catch (MappingException $e) {
            return null;
        }

        return $this->em->find($entityClass, $entityId);
    }

}