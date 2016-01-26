<?php

namespace Application\MainBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface {

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder() {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('application_main');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        $rootNode
            ->children()
                 ->arrayNode('settings')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('category')->end()
                            ->scalarNode('name')->end()
                            ->scalarNode('type')->end()
                            ->arrayNode('data')
                                ->useAttributeAsKey('name')
                                ->prototype('scalar')->end()
                            ->end()
                            ->scalarNode('default')->end()
                            ->end()
                        ->end()
                    ->end()
                
                ->arrayNode('user_settings')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('category')->end()
                            ->scalarNode('name')->end()
                            ->scalarNode('type')->end()
                            ->arrayNode('data')
                                ->useAttributeAsKey('name')
                                ->prototype('scalar')->end()
                            ->end()
                            ->scalarNode('default')->end()
                            ->end()
                        ->end()
                    ->end()
                
                ->arrayNode('main_menu')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('enabled')->defaultTrue()->end()
                            ->scalarNode('label')->end()
                            ->scalarNode('translation_domain')->defaultNull()->end()
                            ->scalarNode('route')->end()
                            ->arrayNode('routeParameters')
                                ->useAttributeAsKey('name')
                                ->prototype('scalar')->end()
                            ->end()
                            ->scalarNode('icon')->end()
                            ->scalarNode('role')->end()
                            ->arrayNode('children')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('enabled')->defaultTrue()->end()
                                        ->scalarNode('label')->end()
                                        ->scalarNode('translation_domain')->defaultNull()->end()
                                        ->scalarNode('route')->end()
                                        ->arrayNode('routeParameters')
                                            ->useAttributeAsKey('name')
                                            ->prototype('scalar')->end()
                                        ->end()
                                        ->scalarNode('icon')->end()
                                        ->scalarNode('role')->end()
                                    ->end()
                                ->end()                
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }

}
