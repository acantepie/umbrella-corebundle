<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 25/03/20
 * Time: 22:32
 */

namespace Umbrella\CoreBundle\Component\DataTable\RowAction;

use Twig\Environment;

/**
 * Class RowActionRenderer
 */
class RowActionRenderer
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * UmbrellaRowActionRenderer constructor.
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param RowAction $rowAction
     * @return mixed
     */
    public function render(RowAction $rowAction)
    {
        return $this->twig->render('@UmbrellaCore/RowAction/row_action.html.twig', array(
            'action' => $rowAction
        ));
    }

}