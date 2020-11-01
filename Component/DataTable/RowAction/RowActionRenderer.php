<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 25/03/20
 * Time: 22:32
 */

namespace Umbrella\CoreBundle\Component\DataTable\RowAction;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Umbrella\CoreBundle\Utils\HtmlUtils;

/**
 * Class RowActionRenderer
 */
class RowActionRenderer
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * RowActionRenderer constructor.
     *
     * @param RouterInterface     $router
     * @param TranslatorInterface $translator
     */
    public function __construct(RouterInterface $router, TranslatorInterface $translator)
    {
        $this->router = $router;
        $this->translator = $translator;
    }

    /**
     * Don't use twig template to avoid performance issue
     *
     * @param RowAction $rowAction
     *
     * @return string
     */
    public function render(RowAction $rowAction)
    {
        $url = empty($rowAction->getRoute())
            ? $rowAction->getUrl()
            : $this->router->generate($rowAction->getRoute(), $rowAction->getRouteParams());

        $attr = [];
        $attr['class'] = $rowAction->getClass();

        if ($rowAction->isXhr()) {
            $attr['data-xhr'] = $url;
            $attr['href'] = '';

            if ($rowAction->isSpinner()) {
                $attr['data-spinner'] = 'true';
            }

            if (!empty($rowAction->getConfirm())) {
                $attr['data-confirm'] = $this->translator->trans($rowAction->getConfirm());
            }

            if (!empty($rowAction->getXhrId())) {
                $attr['data-xhr-id'] = $rowAction->getXhrId();
            }
        } else {
            $attr['href'] = $url;
            $attr['target'] = $rowAction->getTarget();
        }

        if (!empty($rowAction->getTitle())) {
            $attr['title'] = $this->translator->trans($rowAction->getTitle());
            $attr['data-toggle'] = 'tooltip';
        }

        $html = sprintf('<a %s>', HtmlUtils::array_to_html_attribute($attr));

        if (!empty($rowAction->getIcon())) {
            $html .= HtmlUtils::render_icon($rowAction->getIcon());
        }

        $html .= '</a>';

        return $html;
    }
}
