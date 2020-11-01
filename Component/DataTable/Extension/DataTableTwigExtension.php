<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 14/05/17
 * Time: 18:46.
 */

namespace Umbrella\CoreBundle\Component\DataTable\Extension;

use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Umbrella\CoreBundle\Component\Column\Column;
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
     *
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
            new TwigFunction('render_column_header', [$this, 'renderColumn'], [
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
        $view = $table->createView($this->translator);

        return $twig->render($view->template, $view->vars);
    }

    /**
     * @param Environment $twig
     * @param Column      $column
     *
     * @return string
     */
    public function renderColumn(Environment $twig, Column $column)
    {
        $view = $column->createView();

        return $twig->render($view->template, $view->vars);
    }
}
