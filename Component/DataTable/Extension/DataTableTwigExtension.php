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
        return array(
            new TwigFunction('render_table', array($this, 'render'), array(
                'is_safe' => array('html'),
                'needs_environment' => true,
            )),
        );
    }

    /**
     * @param Environment $twig
     * @param AbstractDataTable $table
     *
     * @return string
     */
    public function render(Environment $twig, AbstractDataTable $table)
    {
        return $twig->render($table->getTemplate(), $table->getViewOptions($this->translator));
    }
}
