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
        );
    }

    /**
     * @param Environment $twig
     * @param Toolbar $toolbar
     * @return string
     */
    public function render(Environment $twig, Toolbar $toolbar)
    {

        $options = array();
        $options['toolbar'] = $toolbar;
        $options['form'] = $toolbar->form ? $toolbar->form ->createView() : null;
        $options['actions'] = $toolbar->actions;
        $options['class'] = $toolbar->class;

        return $twig->render($toolbar->template, $options);
    }
}
