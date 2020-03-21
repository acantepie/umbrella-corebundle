<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 08/05/17
 * Time: 22:58.
 */

namespace Umbrella\CoreBundle\Component\AppProxy;

/**
 * Class AppMessage.
 */
class AppMessage implements \JsonSerializable
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
     * @var string
     */
    public $action;

    /**
     * @var string
     */
    public $params = array();

    /**
     * AppMessage constructor.
     *
     * @param $action
     * @param array $params
     */
    public function __construct($action, $params = array())
    {
        $this->action = $action;
        $this->params = $params;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return array(
            'action' => $this->action,
            'params' => $this->params,
        );
    }
}
