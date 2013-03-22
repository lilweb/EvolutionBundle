<?php
/**
 * User: Geoffrey Brier
 * Date: 18/03/13
 * Time: 14:32
 */
namespace Lilweb\EvolutionBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface,
    Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Configuration for the evoluion extension.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('lilweb_evolution');
        $rootNode
            ->children()
                ->scalarNode('evolution_dir')->isRequired()->cannotBeEmpty()->end()
            ->end();

        return $treeBuilder;
    }
}