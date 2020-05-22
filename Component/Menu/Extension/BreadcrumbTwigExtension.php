<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 08/05/17
 * Time: 16:59.
 */

namespace Umbrella\CoreBundle\Component\Menu\Extension;

use Twig\Environment;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
use Symfony\Contracts\Translation\TranslatorInterface;

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
        return [
            new TwigFunction('breadcrumb_render', [$this, 'render'], ['needs_environment' => true, 'is_safe' => ['html']]),
        ];
    }

    /**
     * @param  Environment $environment
     * @param  array       $bc
     * @return string
     */
    public function render(Environment $environment, array $bc = [])
    {
        return $environment->render('@UmbrellaCore/Breadcrumb/breadcrumb.html.twig', [
            'breadcrumb' => $bc
        ]);
    }
}
