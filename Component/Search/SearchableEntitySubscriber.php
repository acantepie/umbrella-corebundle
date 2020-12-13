<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 28/05/17
 * Time: 12:58.
 */

namespace Umbrella\CoreBundle\Component\Search;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Umbrella\CoreBundle\Component\Search\Annotation\SearchableAnnotationReader;

/**
 * Class EntitySubscriber.
 */
class SearchableEntitySubscriber implements EventSubscriber
{
    /**
     * @var EntityIndexer
     */
    private $entityIndexer;

    /**
     * SearchableEntitySubscriber constructor.
     * @param EntityIndexer $entityIndexer
     */
    public function __construct(EntityIndexer $entityIndexer)
    {
        $this->entityIndexer = $entityIndexer;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args) : void
    {
        $this->entityIndexer->indexEntity($args->getObject());
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args) : void
    {
        $this->entityIndexer->indexEntity($args->getObject());
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents() : array
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }
}
