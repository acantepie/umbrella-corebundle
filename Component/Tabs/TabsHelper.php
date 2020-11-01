<?php

namespace Umbrella\CoreBundle\Component\Tabs;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Yaml\Yaml;
use Umbrella\CoreBundle\Utils\ArrayUtils;
use Umbrella\CoreBundle\Utils\HtmlUtils;

/**
 * Class TabsHelper
 */
class TabsHelper
{
    const DEFAULT_CONFIG = 'default';

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var string
     */
    private $configPath;

    /**
     * @var bool
     */
    private $_initialized = false;

    /**
     * @var array
     */
    private $_configs = [];

    /**
     * @var array
     */
    private $_currentConfig;

    /**
     * @var int
     */
    private $_navItemCount = 0;

    /**
     * TabsHelper constructor.
     *
     * @param RequestStack $requestStack
     * @param RouterInterface $router
     * @param $configPath
     */
    public function __construct(RequestStack $requestStack, RouterInterface $router, $configPath)
    {
        $this->requestStack = $requestStack;
        $this->router = $router;
        $this->configPath = $configPath;
    }


    /**
     *
     */
    private function initialize()
    {
        if (!$this->_initialized) {
            $this->_initialized = true;
            $configs = (array) Yaml::parse(file_get_contents($this->configPath));

            $baseConfig = $configs['base'];
            unset($configs['base']);

            foreach ($configs as $configName => $config) {
                $this->_configs[$configName] = ArrayUtils::array_merge_recursive($baseConfig, $config);
            }

            $this->_currentConfig = $this->_configs[self::DEFAULT_CONFIG];
        }
    }

    /**
     * @param string $configName
     * @param array $config
     */
    public function navConfig(array $config = [], $configName = self::DEFAULT_CONFIG)
    {
        $this->initialize();

        if (!isset($this->_configs[$configName])) {
            throw new \InvalidArgumentException(sprintf('Invalid config name "%". Confis defined are : %s', array_keys($this->_configs)));
        }

        $this->_currentConfig = ArrayUtils::array_merge_recursive($this->_configs[$configName], $config);
    }

    /**
     * @param array $parameters
     */
    public function navStart(array $parameters = [])
    {
        $this->initialize();
        $this->_navItemCount = 0;

        $config = ArrayUtils::array_merge_recursive($this->_currentConfig['nav'], $parameters);
        return sprintf('<ul %s>', HtmlUtils::array_to_html_attribute($config['attr']));
    }

    /**
     *
     */
    public function navEnd()
    {
        $this->initialize();
        return sprintf('</ul>');
    }

    /**
     * @param array $parameters
     */
    public function navItem(array $parameters = [])
    {
        $this->initialize();
        $this->_navItemCount++;

        $config = ArrayUtils::array_merge_recursive($this->_currentConfig['nav_item'], $parameters);

        $html = sprintf('<li %s>', HtmlUtils::array_to_html_attribute($config['attr']));

        if ($config['route']) {
            $config['attr_link']['href'] = $this->router->generate($config['route'], $config['route_params']);
        } else {
            $config['attr_link']['href'] = $config['url'];
        }

        if (substr($config['attr_link']['href'], 0, 1) === '#') { // anchor
            $config['attr_link']['data-toggle'] = 'tab';
        }

        if ($this->isActive($config)) {
            $config['attr_link']['class'] .= ' active';
        }

        $html .= sprintf('<a %s>', HtmlUtils::array_to_html_attribute($config['attr_link']));

        if ($config['icon']) {
            $html .= HtmlUtils::render_icon($config['icon']);
        }

        if ($config['label']) {
            $html .= sprintf('<span>%s</span>', $config['label']);
        }

        $html .= '</a>';
        $html .= '</li>';

        return $html;
    }

    /**
     * @param array $navItemConfig
     */
    private function isActive(array $navItemConfig = [])
    {
        $activeStrategy = $this->_currentConfig['active_strategy'];

        switch($activeStrategy) {
            case 'first':
                return $this->_navItemCount === 1;

            case 'current_route':
                $currentRoute = $this->requestStack->getMasterRequest()->get('_route');
                return $navItemConfig['route'] === $currentRoute;

            default:
                return (bool) $navItemConfig['active'];
        }

    }


}