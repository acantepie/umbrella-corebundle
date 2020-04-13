<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 08/05/17
 * Time: 01:46.
 */

namespace Umbrella\CoreBundle\Component\JsResponse;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Umbrella\CoreBundle\Component\Menu\MenuHelper;

/**
 * Class JsResponseBuilder
 */
class JsResponseBuilder
{

    const TOAST = 'toast';
    const EXECUTE_JS = 'execute_js';
    const REDIRECT = 'redirect';
    const RELOAD = 'reload';

    const UPDATE_HTML = 'update';
    const REMOVE_HTML = 'remove';

    const OPEN_MODAL = 'open_modal';
    const CLOSE_MODAL = 'close_modal';

    const RELOAD_TREE = 'reload_tree';
    const RELOAD_TABLE = 'reload_table';
    const RELOAD_MENU = 'reload_menu';
    
    /**
     * @var array
     */
    private $messages = array();

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var MenuHelper
     */
    private $menuHelper;

    /**
     * AppMessageBuilder constructor.
     *
     * @param RouterInterface $router
     * @param TranslatorInterface $translator
     * @param Environment $twig
     * @param MenuHelper $menuHelper
     */
    public function __construct(RouterInterface $router, TranslatorInterface $translator, Environment $twig, MenuHelper $menuHelper)
    {
        $this->router = $router;
        $this->translator = $translator;
        $this->twig = $twig;
        $this->menuHelper = $menuHelper;
    }


    /**
     * @param $action
     * @param array $params
     *
     * @return JsResponseBuilder
     */
    public function add($action, $params = array())
    {
        $this->messages[] = new JsMessage($action, $params);
        return $this;
    }

    /**
     * Clear all messages.
     * @return $this
     */
    public function clear()
    {
        $this->messages = array();
        return $this;
    }

    /**
     *
     */
    private function orderActions()
    {
        uasort($this->messages, function (JsMessage $a, JsMessage $b) {
            return $a->compare($b);
        });
    }

    /**
     * @return JsResponse
     */
    public function getResponse()
    {
        $this->orderActions();
        return new JsResponse($this->messages);
    }

    /**
     * @inheritdoc
     */
    public function getIterator()
    {
        $this->orderActions();
        return new \ArrayIterator($this->messages);
    }

    /**
     * @inheritdoc
     */
    public function count()
    {
        return count($this->messages);
    }



    // Misc actions

    public function toast($id, array $parameters = array(), $level = 'success')
    {
        return $this->add(self::TOAST, array(
            'value' => $this->translator->trans($id, $parameters),
            'level' => $level,
        ));
    }

    public function toastInfo($id, array $params = array())
    {
        return $this->toast($id, $params, 'info');
    }

    public function toastSuccess($id, array $params = array())
    {
        return $this->toast($id, $params, 'success');
    }

    public function toastWarning($id, array $params = array())
    {
        return $this->toast($id, $params, 'warning');
    }

    public function toastError($id, array $params = array())
    {
        return $this->toast($id, $params, 'error');
    }

    public function redirectToRoute($route, array $params = array())
    {
        return $this->redirect($this->router->generate($route, $params));
    }

    public function redirect($url)
    {
        return $this->add(self::REDIRECT, array(
            'value' => $url,
        ));
    }

    public function reload()
    {
        return $this->add(self::RELOAD);
    }

    public function execute($js)
    {
        return $this->add(self::EXECUTE_JS, array(
            'value' => $js,
        ));
    }

    // Html actions

    public function update($css_selector, $html)
    {
        return $this->addHtmlMessage(self::UPDATE_HTML, $html, $css_selector);
    }

    public function updateView($css_selector, $template, array $context = array())
    {
        return $this->update($css_selector, $this->twig->render($template, $context));
    }

    public function remove($css_selector)
    {
        return $this->addHtmlMessage(self::REMOVE_HTML, null, $css_selector);
    }


    // Modal actions

    public function openModal($html)
    {
        return $this->addHtmlMessage(self::OPEN_MODAL, $html);
    }

    public function openModalView($template, array $context = array())
    {
        return $this->openModal($this->twig->render($template, $context));
    }

    public function closeModal()
    {
        return $this->addHtmlMessage(self::CLOSE_MODAL);
    }

    // Components actions

    public function reloadTable($ids = null)
    {
        return $this->add(self::RELOAD_TABLE, array(
            'ids' => (array) $ids,
        ));
    }

    public function reloadMenu($id, $container_selector = '#aside')
    {
        $menu = $this->menuHelper->getMenu($id);
        $html = $this->menuHelper->getRenderer($id)->render($menu);
        return $this->update($container_selector, $html);
    }

    public function reloadTree($id)
    {
        return $this->add(self::RELOAD_TREE, array(
            'id' => $id,
        ));
    }


    // Utils

    /**
     * @param $type
     * @param $html
     * @param $css_selector
     *
     * @return JsResponseBuilder
     */
    private function addHtmlMessage($type, $html = null, $css_selector = null)
    {
        return $this->add($type, array(
            'value' => $html,
            'selector' => $css_selector,
        ));
    }
}
