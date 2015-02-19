<?php

namespace Truelab\KottiFrontendBundle\DependencyInjection\CompilerPass;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class HtmlBodyProcessorCompilerPass
 * @package Truelab\KottiFrontendBundle\DependencyInjection\CompilerPass
 */
class HtmlBodyProcessorCompilerPass implements CompilerPassInterface
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
        if (!$container->hasDefinition('truelab_kotti_frontend.html_body_processor_manager')) {
            return;
        }

        $definition = $container->getDefinition(
            'truelab_kotti_frontend.html_body_processor_manager'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'truelab_kotti_frontend.html_processor'
        );

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall(
                'addProcessor',
                array(new Reference($id))
            );
        }
    }
}
