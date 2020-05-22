<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 05/05/18
 * Time: 16:10
 */

namespace Umbrella\CoreBundle\Listener;

use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Umbrella\CoreBundle\Component\JsResponse\JsResponseBuilder;

/**
 * Class UmbrellaViewResponseListener
 */
class UmbrellaViewResponseListener implements EventSubscriberInterface
{
    /**
     * @param ViewEvent $event
     */
    public function onKernelView(ViewEvent $event)
    {
        $result = $event->getControllerResult();
        if ($result instanceof JsResponseBuilder) {
            $event->setResponse($result->getResponse());
        }
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['onKernelView', 40],
        ];
    }
}
