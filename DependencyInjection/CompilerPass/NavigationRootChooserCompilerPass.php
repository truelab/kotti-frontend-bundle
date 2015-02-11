<?php

namespace Truelab\KottiFrontendBundle\DependencyInjection\CompilerPass;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class NavigationRootChooserCompilerPass
 * @package Truelab\KottiFrontendBundle\DependencyInjection\CompilerPass
 */
class NavigationRootChooserCompilerPass implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        $container->getDefinition('truelab_kotti_frontend.services.current_context')
            ->setArguments(array(new Reference($container->getParameter('truelab_kotti_frontend.navigation_root_chooser'))));
    }
}
