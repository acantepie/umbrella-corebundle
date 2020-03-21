<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 31/05/17
 * Time: 20:15
 */

namespace Umbrella\CoreBundle\Component\Webpack\Twig;

use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class WebpackTwigExtension
 */
class WebpackTwigExtension  extends AbstractExtension
{

    /**
     * @var Packages
     */
    private $packages;

    /**
     * @var string
     */
    private $env;

    /**
     * @var bool
     */
    private $devServerEnabled;

    /**
     * @var string
     */
    private $devServerHost;

    /**
     * @var int
     */
    private $devServerPort;

    /**
     * WebpackTwigExtension constructor.
     * @param Packages $packages
     * @param KernelInterface $kernel
     */
    public function __construct(Packages $packages, KernelInterface $kernel)
    {
        $this->packages = $packages;
        $this->env = $kernel->getEnvironment();
    }

    /* Call by Bundle configurator */

    public function loadConfig(array $config)
    {
        $this->devServerEnabled = $config['dev_server_enable'];
        $this->devServerHost = $config['dev_server_host'];
        $this->devServerPort = $config['dev_server_port'];
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new TwigFunction('webpack_asset', array($this, 'getAssetUrl')),
        );
    }

    /**
     * @param $path
     * @param null $packageName
     * @return string
     */
    public function getAssetUrl($path, $packageName = null)
    {
        if ($this->env == 'dev' && $this->devServerEnabled) {
            return $this->devServerHost . ':' . $this->devServerPort . $this->packages->getUrl($path, $packageName);
        } else {
            return $this->packages->getUrl($path, $packageName);
        }
    }
}