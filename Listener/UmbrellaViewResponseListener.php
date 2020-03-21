<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 05/05/18
 * Time: 16:10
 */

namespace Umbrella\CoreBundle\Listener;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Umbrella\CoreBundle\Component\AppProxy\AppMessageBuilder;

/**
 * Class UmbrellaViewResponseListener
 */
class UmbrellaViewResponseListener implements EventSubscriberInterface
{

    /**
     * @param GetResponseForControllerResultEvent $event
     */
    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $result = $event->getControllerResult();
        if ($result instanceof AppMessageBuilder) {
            $event->setResponse($result->getResponse());
        }
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::VIEW => array('onKernelView', 40),
        );
    }
}