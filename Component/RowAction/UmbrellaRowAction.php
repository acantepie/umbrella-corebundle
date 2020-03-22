<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 09/02/18
 * Time: 20:07
 */

namespace Umbrella\CoreBundle\Component\RowAction;

use Umbrella\CoreBundle\Utils\HtmlUtils;

/**
 * Class UmbrellaRowAction
 */
class UmbrellaRowAction
{
    const TARGET_SELF = '_self';
    const TARGET_BLANK = '_blank';

    /**
     * @var string
     */
    public $icon;

    /**
     * @var string
     */
    public $class;

    /**
     * @var string
     */
    public $url;

    /**
     * @var string
     */
    public $label;

    /**
     * @var string
     */
    public $confirm;

    /**
     * @var bool
     */
    public $spinner = false;

    /**
     * @var boolean
     */
    public $xhr;

    /**
     * @var string
     */
    public $target;

    /**
     * @return string
     */
    public function render()
    {
        $html = '';

        $html .= "<a class=\"row-action $this->class\" {$this->renderHref()} title=\"$this->label\"";
        if (!empty($this->confirm)) {
            $html .= " data-confirm=\"$this->confirm\"";
        }

        if ($this->spinner === true) {
            $html .= " data-spinner=\"true\"";
        }

        if (!empty($this->target)) {
            $html .= "target=\"$this->target\"";
        }

        $html .= '>';
        if ($this->icon) {
            $html .= HtmlUtils::render_icon($this->icon);
        } else {
            $html .= $this->label;
        }

        $html .= "</a>";
        return $html;
    }

    /**
     * @return string
     */
    private function renderHref()
    {
        return $this->xhr
            ? "data-xhr-href=\"$this->url\" href"
            : "href=\"$this->url\"";

    }


}