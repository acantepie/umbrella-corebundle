<?php

namespace Umbrella\CoreBundle\Component\Action\Extension;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
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
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * ActionTwigExtension constructor.
     * @param RouterInterface $router
     * @param TranslatorInterface $translator
     */
    public function __construct(RouterInterface $router, TranslatorInterface $translator)
    {
        $this->router = $router;
        $this->translator = $translator;
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
     * @param Environment $twig
     * @param Action      $action
     *
     * @return string
     */
    public function render(Environment $twig, Action $action)
    {
        $view = $action->createView($this->router, $this->translator);

        return $twig->render($view->template, $view->vars);
    }
}
