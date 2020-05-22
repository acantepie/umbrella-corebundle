<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 14/05/17
 * Time: 18:46.
 */

namespace Umbrella\CoreBundle\Component\Toolbar\Extension;

use Twig\Environment;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
use Umbrella\CoreBundle\Component\Toolbar\Toolbar;
use Umbrella\CoreBundle\Component\Toolbar\Action\Action;

/**
 * Class ToolbarTwigExtension.
 */
class ToolbarTwigExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('render_toolbar', [$this, 'render'], [
                'is_safe' => ['html'],
                'needs_environment' => true,
            ]),
            new TwigFunction('render_action', [$this, 'renderAction'], [
                'is_safe' => ['html'],
                'needs_environment' => true,
            ]),
        ];
    }

    /**
     * @param  Environment $twig
     * @param  Toolbar     $toolbar
     * @return string
     */
    public function render(Environment $twig, Toolbar $toolbar)
    {
        return $twig->render($toolbar->getTemplate(), $toolbar->getViewOptions());
    }

    /**
     * @param  Environment $twig
     * @param  Action      $action
     * @return string
     */
    public function renderAction(Environment $twig, Action $action)
    {
        return $twig->render($action->getTemplate(), $action->getViewOptions());
    }
}
