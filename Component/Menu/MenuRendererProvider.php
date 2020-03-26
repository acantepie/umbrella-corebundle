<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 14/05/17
 * Time: 11:30.
 */

namespace Umbrella\CoreBundle\Component\Menu;

use Umbrella\CoreBundle\Component\Menu\Renderer\MenuRendererInterface;

/**
 * Class MenuRendererProvider.
 */
class MenuRendererProvider
{
    /**
     * @var array
     */
    private $renderers = array();

    /**
     * @param $alias
     * @param MenuRendererInterface $renderer
     */
    public function register($alias, MenuRendererInterface $renderer)
    {
        $this->renderers[$alias] = $renderer;
    }

    /**
     * @param $name
     * @return MenuRendererInterface
     */
    public function get($name)
    {
        if (!isset($this->renderers[$name])) {
            throw new \InvalidArgumentException(sprintf('The menu renderer "%s" is not defined.', $name));
        }

        return $this->renderers[$name];
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public function has($name)
    {
        return isset($this->renderersId[$name]);
    }
}
