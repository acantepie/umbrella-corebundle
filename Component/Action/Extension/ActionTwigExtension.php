<?php

namespace Umbrella\CoreBundle\Component\Action\Extension;

use Twig\Environment;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
use Symfony\Component\Routing\RouterInterface;
use Umbrella\CoreBundle\Component\Action\Action;

/**
 * Class DataTableTwigExtension.
 */
class ActionTwigExtension extends AbstractExtension
{

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * ActionTwigExtension constructor.
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('render_action', [$this, 'render'], [
                'is_safe' => ['html'],
                'needs_environment' => true,
            ]),
        ];
    }

    /**
     * @param  Environment $twig
     * @param  Action      $action
     * @return string
     */
    public function render(Environment $twig, Action $action)
    {
        $view = $action->createView($this->router);
        return $twig->render($view->template, $view->vars);
    }
}
