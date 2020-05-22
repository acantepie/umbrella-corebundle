<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 14/05/17
 * Time: 18:46.
 */

namespace Umbrella\CoreBundle\Component\DataTable\Extension;

use Twig\Environment;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
use Symfony\Contracts\Translation\TranslatorInterface;
use Umbrella\CoreBundle\Component\DataTable\Model\AbstractDataTable;

/**
 * Class DataTableTwigExtension.
 */
class DataTableTwigExtension extends AbstractExtension
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * DataTableTwigExtension constructor.
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
            new TwigFunction('render_table', [$this, 'render'], [
                'is_safe' => ['html'],
                'needs_environment' => true,
            ]),
        ];
    }

    /**
     * @param Environment       $twig
     * @param AbstractDataTable $table
     *
     * @return string
     */
    public function render(Environment $twig, AbstractDataTable $table)
    {
        return $twig->render($table->getTemplate(), $table->getViewOptions($this->translator));
    }
}
