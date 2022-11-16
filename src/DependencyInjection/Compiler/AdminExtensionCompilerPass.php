<?php

namespace Aschaeffer\SonataEditableListBundle\DependencyInjection\Compiler;

use Aschaeffer\SonataEditableListBundle\Annotation\Listable;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AdminExtensionCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $annotationReader = $container->get('annotations.reader');

        foreach ($container->findTaggedServiceIds('sonata.admin') as $id => $attributes) {
            $admin = $container->getDefinition($id);
            $arguments = $admin->getArguments();
            if (!isset($arguments[1])) {
                continue;
            }

            $modelClass = $container->getParameterBag()->resolveValue($arguments[1]);
            if (!$modelClass || !class_exists($modelClass)) {
                continue;
            }
            $modelClassReflection = new \ReflectionClass($modelClass);

            foreach ($modelClassReflection->getProperties() as $reflectionProperty) {
                if ($annotationReader->getPropertyAnnotation($reflectionProperty, Listable::ANNOTATION_NAME)) {
                    $adminExtensionReference = new Reference('aschaeffer.sonataeditablelist.admin.extension.listable');
                    $admin->addMethodCall('addExtension', [$adminExtensionReference]);
                    break;
                }
            }
        }
    }
}
