<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 05/05/18
 * Time: 16:10
 */

namespace Umbrella\CoreBundle\Component\JsResponse;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class JsResponseViewListener
 */
class JsResponseViewListener implements EventSubscriberInterface
{
    /**
     * @param ViewEvent $event
     */
    public function onKernelView(ViewEvent $event): void
    {
        $result = $event->getControllerResult();
        if ($result instanceof JsResponseBuilder) {
            $event->setResponse($result->getResponse());
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['onKernelView', 40],
        ];
    }
}
