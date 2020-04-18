<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 14/05/17
 * Time: 18:46.
 */

namespace Umbrella\CoreBundle\Component\Table\Extension;

use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Umbrella\CoreBundle\Component\Table\Model\Table;

/**
 * Class DataTableTwigExtension.
 */
class TableTwigExtension extends AbstractExtension
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
     * @param Table $table
     *
     * @return string
     */
    public function render(Environment $twig, Table $table)
    {
        return $twig->render($table->getTemplate(), $table->getViewOptions($this->translator));
    }
}
