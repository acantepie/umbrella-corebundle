<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 19/04/20
 * Time: 11:56
 */

namespace Umbrella\CoreBundle\Component\Toast;

use Umbrella\CoreBundle\Component\ComponentView;

/**
 * Class Toast
 */
class Toast
{
    const INFO = 'info';
    const SUCCESS = 'success';
    const WARNING = 'warning';
    const ERROR = 'error';

    const BOTTOM_LEFT = 'bottom-left';
    const BOTTOM_RIGHT = 'bottom-right';
    const BOTTOM_CENTER = 'bottom-center';
    const TOP_RIGHT = 'top-right';
    const TOP_LEFT = 'top-left';
    const TOP_CENTER = 'top-center';
    const MID_CENTER = 'mid-center';

    /**
     * @var bool
     */
    private $loader = true;

    /**
     * @var bool
     */
    private $allowToastClose = true;

    /**
     * @var int
     */
    private $hideAfter = 3000;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $text;

    /**
     * @var string
     */
    private $type = self::INFO;

    /**
     * @var string
     */
    private $position = self::BOTTOM_RIGHT;

    /**
     * @return bool
     */
    public function hasLoader()
    {
        return $this->loader;
    }

    /**
     * @param bool $loader
     *
     * @return Toast
     */
    public function setLoader($loader)
    {
        $this->loader = $loader;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAllowToastClose()
    {
        return $this->allowToastClose;
    }

    /**
     * @param bool $allowToastClose
     *
     * @return Toast
     */
    public function setAllowToastClose($allowToastClose)
    {
        $this->allowToastClose = $allowToastClose;

        return $this;
    }

    /**
     * @return int
     */
    public function getHideAfter()
    {
        return $this->hideAfter;
    }

    /**
     * @param int $hideAfter
     *
     * @return Toast
     */
    public function setHideAfter($hideAfter)
    {
        $this->hideAfter = $hideAfter;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return Toast
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     *
     * @return Toast
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return Toast
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param string $position
     *
     * @return Toast
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return ComponentView
     */
    public function createView(): ComponentView
    {
        $view = new ComponentView();
        $view->vars = [
            'heading' => $this->title,
            'text' => $this->text,
            'position' => $this->position,
            'icon' => $this->type,
            'allowToastClose' => $this->allowToastClose,
            'loader' => $this->loader,
            'hideAfter' => $this->hideAfter,
        ];

        return $view;
    }
}
