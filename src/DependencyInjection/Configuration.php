<?php

declare(strict_types=1);

namespace Quickclack\RestAttributeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('rest-attribute-bundle');

        $treeBuilder->getRootNode()
            ->children()
            ->booleanNode('enable_validation')
            ->defaultTrue()
            ->info('Enable parameter validation using Symfony Validator')
            ->end()
            ->scalarNode('default_error_message')
            ->defaultValue('Invalid parameter value')
            ->info('Default error message for validation failures')
            ->end()
            ->end();

        return $treeBuilder;
    }
}