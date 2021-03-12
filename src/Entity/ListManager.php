<?php

namespace Aschaeffer\SonataEditableListBundle\Entity;

use Doctrine\Common\Annotations\AnnotationReader;
use Sonata\DatagridBundle\Pager\Doctrine\Pager;
use Sonata\DatagridBundle\ProxyQuery\Doctrine\ProxyQuery;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sonata\Doctrine\Entity\BaseEntityManager;


class ListManager extends BaseEntityManager
{
    /**
     * @var AnnotationReader
     */
    protected $annotationReader;

    /**
     * EditableListManager constructor.
     * @param $class
     * @param ManagerRegistry $registry
     * @param AnnotationReader $annotationReader
     */
    public function __construct($class, ManagerRegistry $registry, AnnotationReader $annotationReader)
    {
        parent::__construct($class, $registry);

        $this->annotationReader = $annotationReader;
    }

//    public function getPager(array $criteria, $page, $limit = 10, array $sort = [])
//    {
//        $parameters = [];
//
//        $query = $this->getRepository()
//            ->createQueryBuilder('t')
//            ->select('t');
//
//        if (isset($criteria['enabled'])) {
//            $query->andWhere('t.enabled = :enabled');
//            $parameters['enabled'] = (bool) $criteria['enabled'];
//        }
//
//        $query->setParameters($parameters);
//
//        $pager = new Pager();
//        $pager->setMaxPerPage($limit);
//        $pager->setQuery(new ProxyQuery($query));
//        $pager->setPage($page);
//        $pager->init();
//
//        return $pager;
//    }

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
