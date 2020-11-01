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
    private $defaultConfig;

    /**
     * @var array
     */
    private $configs = [];

    /**
     * CkeditorConfiguration constructor.
     *
     * @param array $config
     */
    public function __construct(array $bundleConfig)
    {
        $this->resolveConfig($bundleConfig);
    }

    /**
     * Load default configs
     */
    private function resolveConfig(array $bundleConfig)
    {
        $this->configs['minimal'] = [
            'toolbar' => [
                ['name' => 'clipboard', 'items' => ['Undo', 'Redo']],
                ['name' => 'styles', 'items' => ['Format']],
                ['name' => 'basicstyles', 'items' => ['Bold', 'Italic', 'Strike', '-', 'RemoveFormat']],
                ['name' => 'paragraph', 'items' => ['NumberedList', 'BulletedList']],
                ['name' => 'links', 'items' => ['Link', 'Unlink']],
            ],
            'uiColor' => '#FEFEFE',
        ];

        $this->configs['full'] = [
            'toolbar' => [
                ['name' => 'clipboard', 'items' => ['Undo', 'Redo']],
                ['name' => 'styles', 'items' => ['Format']],
                ['name' => 'basicstyles', 'items' => ['Bold', 'Italic', 'Strike', '-', 'RemoveFormat']],
                ['name' => 'paragraph', 'items' => ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote']],
                ['name' => 'links', 'items' => ['Link', 'Unlink']],
                ['name' => 'insert', 'items' => ['Image', 'oembed', 'Table']],
                ['name' => 'tools', 'items' => ['Maximize', 'Scayt', 'Source']],
            ],
            'uiColor' => '#FEFEFE',
        ];
        $this->configs['all'] = [];

        foreach ($bundleConfig['configs'] as $configName => $config) {
            $this->configs[$configName] = $config;
        }

        if (isset($bundleConfig['default_config']) && isset($this->configs[$bundleConfig['default_config']])) {
            $defaultConfigName = $bundleConfig['default_config'];
        } else {
            $defaultConfigName = 'full';
        }

        $this->defaultConfig = $this->configs[$defaultConfigName];
    }

    /**
     * @param null $name
     *
     * @return array
     */
    public function getConfig($name)
    {
        if (!isset($this->configs[$name])) {
            $configNames = implode(', ', array_keys($this->configs));
            throw new \UnexpectedValueException(sprintf('Config "%s" doesn\'t exist, config available are : %s', $name, $configNames));
        }

        return $this->configs[$name];
    }

    /**
     * @return array
     */
    public function getDefaultConfig()
    {
        return $this->defaultConfig;
    }
}
