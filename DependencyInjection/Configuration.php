<?php

namespace Truelab\KottiFrontendBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('truelab_kotti_frontend');

        $rootNode
            ->children()
                ->scalarNode('node_path_param')
                    ->defaultValue('nodePath')
                ->end()
                ->scalarNode('default_layout')
                    ->defaultValue('@TruelabKottiFrontendBundle/Resources/views/base_layout.html.twig')
                ->end()
                ->scalarNode('domain')
                    ->treatFalseLike(null)
                    ->defaultNull()
                ->end()
                ->scalarNode('image_domain')
                    // FIXME
                    ->treatNullLike('http://localhost:5000')
                    ->treatFalseLike('http://localhost:5000')
                    ->defaultValue('http://localhost:5000')
                ->end()
                ->scalarNode('navigation_root_chooser')
                    ->defaultValue(null)
                ->end()
                ->arrayNode('navigable_context_types')
                    ->defaultValue([
                        'document' => true
                    ])
                    ->useAttributeAsKey('name')
                    ->prototype('scalar')->treatNullLike(true)->end()
                ->end()
                ->arrayNode('view_config_controllers')
                    ->defaultValue([])
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('options')
                    ->useAttributeAsKey('name')
                    ->prototype('scalar')
                ->end()

            ->end();

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
