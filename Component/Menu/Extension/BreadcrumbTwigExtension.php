<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 08/05/17
 * Time: 16:59.
 */

namespace Umbrella\CoreBundle\Component\Menu\Extension;

use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class BreadcrumbTwigExtension.
 */
class BreadcrumbTwigExtension extends AbstractExtension
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * BreadcrumbTwigExtension constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new TwigFunction('breadcrumb_render', array($this, 'render'), array('needs_environment' => true, 'is_safe' => array('html'))),
        );
    }

    /**
     * @param Environment $environment
     * @param array $bc
     * @return string
     */
    public function render(Environment $environment, array $bc = array())
    {
        return $environment->render('@UmbrellaCore/Breadcrumb/breadcrumb.html.twig', array(
            'breadcrumb' => $bc
        ));
    }


}
