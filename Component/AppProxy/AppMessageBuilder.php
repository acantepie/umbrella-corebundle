<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 08/05/17
 * Time: 01:46.
 */

namespace Umbrella\CoreBundle\Component\AppProxy;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Umbrella\CoreBundle\Component\Menu\MenuHelper;

/**
 * Class AppMessageBuilder.
 */
class AppMessageBuilder
{
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
     * Clear all messages.
     * @return $this
     */
    public function clear()
    {
        $this->messages = array();
        return $this;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    // Misc actions

    public function toast($id, array $parameters = array(), $level = 'success')
    {
        return $this->add(AppMessage::TOAST, array(
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
        return $this->add(AppMessage::REDIRECT, array(
            'value' => $url,
        ));
    }

    public function reload()
    {
        return $this->add(AppMessage::RELOAD);
    }

    public function execute($js)
    {
        return $this->add(AppMessage::EXECUTE_JS, array(
            'value' => $js,
        ));
    }

    // Html actions

    public function update($css_selector, $html)
    {
        return $this->addHtmlMessage(AppMessage::UPDATE_HTML, $html, $css_selector);
    }

    public function updateView($css_selector, $template, array $context = array())
    {
        return $this->update($css_selector, $this->twig->render($template, $context));
    }

    public function remove($css_selector)
    {
        return $this->addHtmlMessage(AppMessage::REMOVE_HTML, null, $css_selector);
    }


    // Modal actions

    public function openModal($html)
    {
        return $this->addHtmlMessage(AppMessage::OPEN_MODAL, $html);
    }

    public function openModalView($template, array $context = array())
    {
        return $this->openModal($this->twig->render($template, $context));
    }

    public function closeModal()
    {
        return $this->addHtmlMessage(AppMessage::CLOSE_MODAL);
    }

    // Components actions

    public function reloadTable($id)
    {
        return $this->add(AppMessage::RELOAD_TABLE, array(
            'id' => $id,
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
        return $this->add(AppMessage::RELOAD_TREE, array(
            'id' => $id,
        ));
    }


    // Utils

    /**
     * @param $action
     * @param array $params
     *
     * @return AppMessageBuilder
     */
    private function add($action, $params = array())
    {
        $this->messages[] = new AppMessage($action, $params);

        return $this;
    }

    /**
     * @param $type
     * @param $html
     * @param $css_selector
     *
     * @return AppMessageBuilder
     */
    private function addHtmlMessage($type, $html = null, $css_selector = null)
    {
        return $this->add($type, array(
            'value' => $html,
            'selector' => $css_selector,
        ));
    }

    /**
     * @return JsonResponse
     */
    public function getResponse()
    {
        return new JsonResponse($this->messages);
    }
}
