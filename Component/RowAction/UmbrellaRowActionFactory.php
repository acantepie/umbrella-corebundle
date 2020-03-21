<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 09/02/18
 * Time: 21:15
 */

namespace Umbrella\CoreBundle\Component\RowAction;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class UmbrellaRowActionFactory
 */
class UmbrellaRowActionFactory
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
     * UmbrellaRowFactory constructor.
     * @param RouterInterface $router
     * @param TranslatorInterface $translator
     */
    public function __construct(RouterInterface $router, TranslatorInterface $translator)
    {
        $this->router = $router;
        $this->translator = $translator;
    }

    /**
     * @param $url
     * @param string $label
     * @param string $icon
     * @param string $class
     * @param string $confirm
     * @param bool $xhr
     * @param null $target
     * @param bool $spinner
     *
     * @return UmbrellaRowAction
     */
    public function create($url, $label = '', $icon = '', $class = '', $confirm = '', $xhr = true, $target = null, $spinner = false)
    {
        $a = new UmbrellaRowAction();
        $a->icon = $icon;
        $a->class = $class;
        $a->url = $url;
        $a->label = empty($label) ? $label : $this->translator->trans($label);
        $a->confirm = empty($confirm) ? $confirm : $this->translator->trans($confirm);
        $a->xhr = $xhr;
        $a->target = $target;
        $a->spinner = $spinner;
        return $a;

    }

    /**
     * @param $route
     * @param array $params
     * @param string $label
     * @param string $icon
     * @param string $class
     * @param string $confirm
     * @param bool $xhr
     * @param null $target
     * @param bool $spinner
     * @return UmbrellaRowAction
     */
    public function createFromRoute($route, array $params = array(), $label = '', $icon = '', $class = '', $confirm = '', $xhr = true, $target = null, $spinner = false)
    {
        return $this->create($this->router->generate($route, $params), $label, $icon, $class, $confirm, $xhr, $target, $spinner);
    }

    public function createEdit($route, array $params = array(), $xhr = true)
    {
        return $this->createFromRoute($route, $params, 'action.edit', 'fa fa-pencil', '', '', $xhr);
    }

    public function createDelete($route, array $params = array())
    {
        return $this->createFromRoute($route, $params, 'action.delete', 'fa fa-times', 'text-danger', 'message.delete_confirm');
    }

    public function createShow($route, array $params = array(), $xhr = true)
    {
        return $this->createFromRoute($route, $params, 'action.show', 'fa fa-eye', '', '', $xhr);
    }

    public function createAddChild($route, array $params = array(), $xhr = true)
    {
        return $this->createFromRoute($route, $params, 'action.add_child', 'fa fa-plus', '', '', $xhr);
    }



}