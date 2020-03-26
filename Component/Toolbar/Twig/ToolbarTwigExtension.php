<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 14/05/17
 * Time: 18:46.
 */

namespace Umbrella\CoreBundle\Component\Toolbar\Twig;

use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Umbrella\CoreBundle\Component\Toolbar\Model\Action;
use Umbrella\CoreBundle\Component\Toolbar\Model\Toolbar;

/**
 * Class DataTableTwigExtension.
 */
class ToolbarTwigExtension extends AbstractExtension
{

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new TwigFunction('render_toolbar', array($this, 'render'), array(
                'is_safe' => array('html'),
                'needs_environment' => true,
            )),
            new TwigFunction('render_action', array($this, 'renderAction'), array(
                'is_safe' => array('html'),
                'needs_environment' => true,
            )),
        );
    }

    /**
     * @param Environment $twig
     * @param Toolbar $toolbar
     * @return string
     */
    public function render(Environment $twig, Toolbar $toolbar)
    {
        return $twig->render($toolbar->getTemplate(), $toolbar->getViewOptions());
    }

    /**
     * @param Environment $twig
     * @param Action $action
     * @return string
     */
    public function renderAction(Environment $twig, Action $action)
    {
        return $twig->render($action->getTemplate(), $action->getViewOptions());
    }
}
