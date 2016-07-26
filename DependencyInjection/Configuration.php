<?php

namespace Ext\DirectBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
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
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ext_direct');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        $rootNode->children()
            ->scalarNode('error_template')
                ->defaultValue('ExtDirectBundle::extjs_errors.html.twig')
            ->end()
            ->scalarNode('type')
                ->defaultValue('remoting')
            ->end()
            ->scalarNode('namespace')
                ->defaultValue('Actions')
            ->end()
            ->scalarNode('resource')
                ->defaultNull()
            ->end()
            ->scalarNode('param_converter')
                ->defaultValue(false)
            ->end()
        ->end();
        
        return $treeBuilder;
    }

}
