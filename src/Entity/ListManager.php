<?php

namespace Aschaeffer\SonataEditableListBundle\Entity;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Persistence\ManagerRegistry;
use Sonata\Doctrine\Entity\BaseEntityManager;


class ListManager extends BaseEntityManager
{
    protected AnnotationReader $annotationReader;

    public function __construct(string $class, ManagerRegistry $registry, AnnotationReader $annotationReader)
    {
        parent::__construct($class, $registry);

        $this->annotationReader = $annotationReader;
    }

    public function getEntitiesUsage($code = null)
    {
        $classNames = [];

        foreach ($this->getEntityManager()->getMetadataFactory()->getAllMetadata() as $meta) {
            $classNames[] = $meta->getName();
        }

        $usages = [];

        foreach ($classNames as $className) {
            $class = new \ReflectionClass($className);
            foreach ($class->getProperties() as $property) {
                $listable = $this->annotationReader->getPropertyAnnotation($property, 'Aschaeffer\\SonataEditableListBundle\\Annotation\\Listable');
                if (!$listable) {
                    continue;
                }

                if ($code == null) {
                    if (!isset($usages[$listable->getCode()])) {
                        $usages[$listable->getCode()] = [];
                    }
                    $usages[$listable->getCode()][] = [
                        'className' => $className,
                        'property' => $property->getName(),
                    ];
                } else if ($code == $listable->getCode()) {
                    if (!isset($usages[$className])) {
                        $usages[$className] = [];
                    }

                    $usages[$className][] = $property->getName();
                }
            }
        }

        return $usages;
    }
}
