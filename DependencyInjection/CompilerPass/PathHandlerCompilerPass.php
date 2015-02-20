<?php

namespace Truelab\KottiFrontendBundle\DependencyInjection\CompilerPass;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class PathHandlerCompilerPass
 * @package Truelab\KottiFrontendBundle\DependencyInjection\CompilerPass
 */
class PathHandlerCompilerPass implements CompilerPassInterface
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
        if (!$container->hasDefinition('truelab_kotti_frontend.template_api')) {
            return;
        }

        $definition = $container->getDefinition(
            'truelab_kotti_frontend.template_api'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'truelab_kotti_frontend.path_handler'
        );

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall(
                'addPathHandler',
                array(new Reference($id))
            );
        }
    }
}
