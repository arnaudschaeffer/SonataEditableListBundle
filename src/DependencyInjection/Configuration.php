<?php

namespace Aschaeffer\SonataEditableListBundle\DependencyInjection;

use Aschaeffer\SonataEditableListBundle\Admin\ItemAdmin;
use Aschaeffer\SonataEditableListBundle\Admin\ListAdmin;
use Aschaeffer\SonataEditableListBundle\Entity\BaseItem;
use Aschaeffer\SonataEditableListBundle\Entity\BaseList;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
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
        $treeBuilder = new TreeBuilder('aschaeffer_sonataeditablelist');

        // Keep compatibility with symfony/config < 4.2
        if (!method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->root('aschaeffer_sonataeditablelist');
        } else {
            $rootNode = $treeBuilder->getRootNode();
        }

        $rootNode
            ->children()
                ->arrayNode('class')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('list')->cannotBeEmpty()->defaultValue(BaseList::class)->end()
                        ->scalarNode('item')->cannotBeEmpty()->defaultValue(BaseItem::class)->end()
                    ->end()
                ->end()
                ->arrayNode('admin')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('list')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('entity')->cannotBeEmpty()->defaultValue(BaseList::class)->end()
                                ->scalarNode('class')->cannotBeEmpty()->defaultValue(ListAdmin::class)->end()
                                ->scalarNode('controller')->cannotBeEmpty()->defaultValue(CRUDController::class)->end()
                                ->scalarNode('translation')->cannotBeEmpty()->defaultValue('SonataEditableListBundle')->end()
                            ->end()
                        ->end()
                        ->arrayNode('item')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('entity')->cannotBeEmpty()->defaultValue(BaseItem::class)->end()
                                ->scalarNode('class')->cannotBeEmpty()->defaultValue(ItemAdmin::class)->end()
                                ->scalarNode('controller')->cannotBeEmpty()->defaultValue(CRUDController::class)->end()
                                ->scalarNode('translation')->cannotBeEmpty()->defaultValue('SonataEditableListBundle')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
