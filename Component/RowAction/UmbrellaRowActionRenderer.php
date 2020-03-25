<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 25/03/20
 * Time: 22:32
 */

namespace Umbrella\CoreBundle\Component\RowAction;

use Twig\Environment;

/**
 * Class UmbrellaRowActionRenderer
 */
class UmbrellaRowActionRenderer
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
     * @param UmbrellaRowAction $rowAction
     * @return mixed
     */
    public function render(UmbrellaRowAction $rowAction)
    {
        return $this->twig->render('@UmbrellaCore/RowAction/row_action.html.twig', array(
            'action' => $rowAction
        ));
    }

}