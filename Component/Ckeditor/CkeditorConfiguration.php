<?php

namespace Umbrella\CoreBundle\Component\Ckeditor;

/**
 * Class CKEditorConfiguration
 */
class CkeditorConfiguration
{
    /**
     * @var array
     */
    private $defaultConfig = [];

    /**
     * @var array
     */
    private $configs = [];

    /**
     * CkeditorConfiguration constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        if (isset($config['default_config']) && isset($config['configs'][$config['default_config']])) {
            $this->defaultConfig = $config['configs'][$config['default_config']];
        }

        $this->configs = $config['configs'];
    }

    /**
     * @param  null  $name
     * @return array
     */
    public function getConfig($name = null)
    {
        if (null === $name) {
            return $this->defaultConfig;
        }

        if (!isset($this->configs[$name])) {
            throw new \UnexpectedValueException(sprintf('Config "%s" doesn\'t exist, config available are : %s'), implode(', ', array_keys($this->configs)));
        }

        return $this->configs[$name];
    }
}
