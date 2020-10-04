<?php

namespace Umbrella\CoreBundle\Component\Ckeditor;

use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class CkeditorExtension
 */
class CkeditorExtension extends AbstractExtension
{

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * CkeditorExtension constructor.
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('render_ckeditor_js', [$this, 'renderJs'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param array $config
     * @param mixed $id
     */
    public function renderJs($id, array $config)
    {
        return sprintf('CKEDITOR.replace(\'%s\', %s)', $id, json_encode($config));
    }
}
