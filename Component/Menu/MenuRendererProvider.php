<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 14/05/17
 * Time: 11:30.
 */

namespace Umbrella\CoreBundle\Component\Menu;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Umbrella\AdminBundle\Renderer\SideBarMenuRenderer;
use Umbrella\CoreBundle\Component\Menu\Renderer\MenuRendererInterface;

/**
 * Class MenuRendererProvider.
 */
class MenuRendererProvider
{
    /**
     * @var array
     */
    private $renderersId = array();

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * MenuRendererProvider constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param $alias
     * @param $id
     */
    public function register($alias, $id)
    {
        // alias already registered
        if ($id == SideBarMenuRenderer::class && array_key_exists($alias, $this->renderersId)) {
            return;
        }

        $this->renderersId[$alias] = $id;
    }

    /**
     * @param $name
     * @return object|MenuRendererInterface
     */
    public function get($name)
    {
        if (!isset($this->renderersId[$name])) {
            throw new \InvalidArgumentException(sprintf('The menu renderer "%s" is not defined.', $name));
        }

        return $this->container->get($this->renderersId[$name]);
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
