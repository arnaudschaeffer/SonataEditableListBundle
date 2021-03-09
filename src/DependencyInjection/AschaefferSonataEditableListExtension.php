<?php

namespace Aschaeffer\SonataEditableListBundle\DependencyInjection;

use Sonata\Doctrine\Mapper\DoctrineCollector;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;


class AschaefferSonataEditableListExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('orm.xml');
        $loader->load('admin_orm.xml');
        $loader->load('command.xml');

        $this->configureAdminClass($config, $container);
        $this->configureTranslationDomain($config, $container);
        $this->configureClass($config, $container);
    }

    /**
     * @param $config
     * @param ContainerBuilder $container
     */
    public function configureClass($config, ContainerBuilder $container): void
    {
        $container->setParameter('aschaeffer.sonataeditablelist.admin.list.entity', $config['class']['list']);
        $container->setParameter('aschaeffer.sonataeditablelist.admin.item.entity', $config['class']['item']);


        $container->setParameter('aschaeffer.sonataeditablelist.manager.list.entity', $config['class']['list']);
        $container->setParameter('aschaeffer.sonataeditablelist.manager.item.entity', $config['class']['item']);
    }

    /**
     * @param $config
     * @param ContainerBuilder $container
     */
    public function configureAdminClass($config, ContainerBuilder $container): void
    {
        $container->setParameter('aschaeffer.sonataeditablelist.admin.list.class', $config['admin']['list']['class']);
        $container->setParameter('aschaeffer.sonataeditablelist.admin.list.controller', $config['admin']['list']['controller']);
        $container->setParameter('aschaeffer.sonataeditablelist.admin.list.translation_domain', $config['admin']['list']['translation']);

        $container->setParameter('aschaeffer.sonataeditablelist.admin.item.class', $config['admin']['item']['class']);
        $container->setParameter('aschaeffer.sonataeditablelist.admin.item.controller', $config['admin']['item']['controller']);
        $container->setParameter('aschaeffer.sonataeditablelist.admin.item.translation_domain', $config['admin']['item']['translation']);
    }

    /**
     * @param $config
     * @param ContainerBuilder $container
     */
    public function configureTranslationDomain($config, ContainerBuilder $container): void
    {
        $container->setParameter('aschaeffer.sonataeditablelist.admin.list.translation_domain', $config['admin']['list']['translation']);
        $container->setParameter('aschaeffer.sonataeditablelist.admin.item.translation_domain', $config['admin']['item']['translation']);
    }
}
