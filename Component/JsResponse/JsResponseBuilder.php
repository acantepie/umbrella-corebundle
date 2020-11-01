<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 08/05/17
 * Time: 01:46.
 */

namespace Umbrella\CoreBundle\Component\JsResponse;

use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;
use Umbrella\CoreBundle\Component\Menu\MenuHelper;
use Umbrella\CoreBundle\Component\Toast\Toast;
use Umbrella\CoreBundle\Component\Toast\ToastFactory;

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

    const RELOAD_TABLE = 'reload_table';
    const RELOAD_MENU = 'reload_menu';

    /**
     * @var array
     */
    private $messages = [];

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var MenuHelper
     */
    private $menuHelper;

    /**
     * @var ToastFactory
     */
    private $toastFactory;

    /**
     * JsResponseBuilder constructor.
     *
     * @param RouterInterface $router
     * @param Environment     $twig
     * @param MenuHelper      $menuHelper
     * @param ToastFactory    $toastFactory
     */
    public function __construct(RouterInterface $router, Environment $twig, MenuHelper $menuHelper, ToastFactory $toastFactory)
    {
        $this->router = $router;
        $this->twig = $twig;
        $this->menuHelper = $menuHelper;
        $this->toastFactory = $toastFactory;
    }

    /**
     * @param $action
     * @param array $params
     *
     * @return JsResponseBuilder
     */
    public function add($action, $params = [])
    {
        $this->messages[] = new JsMessage($action, $params);

        return $this;
    }

    /**
     * Clear all messages.
     *
     * @return $this
     */
    public function clear()
    {
        $this->messages = [];

        return $this;
    }

    /**
     * @return JsResponse
     */
    public function getResponse()
    {
        $this->orderActions();

        return new JsResponse($this->messages);
    }

    public function getIterator()
    {
        $this->orderActions();

        return new \ArrayIterator($this->messages);
    }

    public function count()
    {
        return count($this->messages);
    }

    // Misc actions

    public function toast(Toast $toast)
    {
        return $this->add(self::TOAST, $toast->createView()->vars);
    }

    public function toastInfo($transId, array $transParams = [])
    {
        return $this->toast($this->toastFactory->createInfo($transId, $transParams));
    }

    public function toastSuccess($transId, array $transParams = [])
    {
        return $this->toast($this->toastFactory->createSuccess($transId, $transParams));
    }

    public function toastWarning($transId, array $transParams = [])
    {
        return $this->toast($this->toastFactory->createWarning($transId, $transParams));
    }

    public function toastError($transId, array $transParams = [])
    {
        return $this->toast($this->toastFactory->createError($transId, $transParams));
    }

    public function redirectToRoute($route, array $params = [])
    {
        return $this->redirect($this->router->generate($route, $params));
    }

    public function redirect($url)
    {
        return $this->add(self::REDIRECT, [
            'value' => $url,
        ]);
    }

    public function reload()
    {
        return $this->add(self::RELOAD);
    }

    public function execute($js)
    {
        return $this->add(self::EXECUTE_JS, [
            'value' => $js,
        ]);
    }

    // Html actions

    public function update($css_selector, $html)
    {
        return $this->addHtmlMessage(self::UPDATE_HTML, $html, $css_selector);
    }

    public function updateView($css_selector, $template, array $context = [])
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

    public function openModalView($template, array $context = [])
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
        return $this->add(self::RELOAD_TABLE, [
            'ids' => (array) $ids,
        ]);
    }

    public function reloadMenu($id, $container_selector = '#aside')
    {
        $menu = $this->menuHelper->getMenu($id);
        $html = $this->menuHelper->getRenderer($id)->render($menu);

        return $this->update($container_selector, $html);
    }

    private function orderActions()
    {
        uasort($this->messages, function (JsMessage $a, JsMessage $b) {
            return $a->compare($b);
        });
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
        return $this->add($type, [
            'value' => $html,
            'selector' => $css_selector,
        ]);
    }
}
