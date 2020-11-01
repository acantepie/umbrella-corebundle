<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 28/05/17
 * Time: 12:58.
 */

namespace Umbrella\CoreBundle\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Umbrella\CoreBundle\Services\SearchHandler;

/**
 * Class EntitySubscriber.
 */
class EntitySubscriber implements EventSubscriber
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var SearchHandler
     */
    private $searchHandler;

    /**
     * EntitySubscriber constructor.
     *
     * @param LoggerInterface $logger
     * @param SearchHandler   $searchHandler
     */
    public function __construct(LoggerInterface $logger, SearchHandler $searchHandler)
    {
        $this->logger = $logger;
        $this->searchHandler = $searchHandler;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->searchHandler->indexEntity($args->getObject());
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->searchHandler->indexEntity($args->getObject());
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }
}
