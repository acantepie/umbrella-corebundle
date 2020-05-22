<?php

namespace Umbrella\CoreBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Umbrella\CoreBundle\DependencyInjection\Compiler\MenuPass;
use Umbrella\CoreBundle\DependencyInjection\Compiler\UmbrellaComponentPass;

/**
 * Class UmbrellaCoreBundle.
 */
class UmbrellaCoreBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new MenuPass());
        $container->addCompilerPass(new UmbrellaComponentPass());
    }
}
