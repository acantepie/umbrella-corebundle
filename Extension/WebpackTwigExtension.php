<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 31/05/17
 * Time: 20:15
 */

namespace Umbrella\CoreBundle\Extension;

use Twig\TwigFunction;
use Symfony\Component\Asset\Packages;
use Twig\Extension\AbstractExtension;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class WebpackTwigExtension
 */
class WebpackTwigExtension extends AbstractExtension
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
     * @param Packages        $packages
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
        return [
            new TwigFunction('webpack_asset', [$this, 'getAssetUrl']),
        ];
    }

    /**
     * @param $path
     * @param  null   $packageName
     * @param  bool   $useDevServer
     * @return string
     */
    public function getAssetUrl($path, $packageName = null, $useDevServer = true)
    {
        if ($this->env == 'dev' && $this->devServerEnabled && $useDevServer) {
            return $this->devServerHost . ':' . $this->devServerPort . $this->packages->getUrl($path, $packageName);
        } else {
            return $this->packages->getUrl($path, $packageName);
        }
    }
}
