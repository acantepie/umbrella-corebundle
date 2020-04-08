<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 08/05/17
 * Time: 22:58.
 */

namespace Umbrella\CoreBundle\Component\JsResponse;

/**
 * Class JsMessage
 */
class JsMessage implements \JsonSerializable
{

    /**
     * @var string
     */
    private $action;

    /**
     * @var string
     */
    private $params = array();

    /**
     * @var int
     */
    private $priority;

    /**
     * JsMessage constructor.
     * @param $action
     * @param array $params
     * @param int $priority
     */
    public function __construct($action, array $params = array(), $priority = 0)
    {
        $this->action = $action;
        $this->params = $params;
        $this->priority = $priority;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return string
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param JsMessage $action
     * @return int
     */
    public function compare(JsMessage $action)
    {
        if ($this->priority == $action->getPriority()) {
            return 0;
        }
        return ($this->priority < $action->getPriority()) ? -1 : 1;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return array(
            'action' => $this->action,
            'params' => $this->params,
        );
    }
}
