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
            ])
        ];
    }

    /**
     * @param  Environment $twig
     * @param  Toolbar     $toolbar
     * @return string
     */
    public function render(Environment $twig, Toolbar $toolbar)
    {
        $view = $toolbar->createView();
        return $twig->render($view->template, $view->vars);
    }
}
