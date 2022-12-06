<?php

namespace Aschaeffer\SonataEditableListBundle\Entity;

use Aschaeffer\SonataEditableListBundle\Annotation\Listable;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Sonata\AdminBundle\Datagrid\SimplePager;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Sonata\Doctrine\Entity\BaseEntityManager;
use Aschaeffer\SonataEditableListBundle\Model\ItemManagerInterface;

class ItemManager extends BaseEntityManager implements ItemManagerInterface
{
    protected AnnotationReader $annotationReader;

    public function __construct(string $class,
                                ManagerRegistry $registry,
                                AnnotationReader $annotationReader)
    {
        parent::__construct($class, $registry);

        $this->annotationReader = $annotationReader;
    }

    public function getPager(array $criteria, $page, $limit = 10, array $sort = [])
    {
        $parameters = [];

        $query = $this->getRepository()
            ->createQueryBuilder('t')
            ->select('t');

        if (isset($criteria['enabled'])) {
            $query->andWhere('t.enabled = :enabled');
            $parameters['enabled'] = (bool) $criteria['enabled'];
        }

        $query->setParameters($parameters);

        $pager = new SimplePager();
        $pager->setMaxPerPage($limit);
        $pager->setQuery(new ProxyQuery($query));
        $pager->setPage($page);
        $pager->init();

        return $pager;
    }

    public function getListableCode($className, $propertyName)
    {
        $class = new \ReflectionClass($className);
        $property = $class->getProperty($propertyName);

        if (!$property) {
            return null;
        }

        $listable = $this->annotationReader->getPropertyAnnotation($property, Listable::ANNOTATION_NAME);

        if (!$listable) {
            return null;
        }

        return $listable->getCode();
    }

    /**
     * @param $propertyName
     * @param $subject
     * @param $className
     * @param bool $flip
     * @return array|null
     */
    public function getListChoices($propertyName, $subject, $className, bool $flip = false)
    {
        $code = $this->getListableCode($className, $propertyName);
        $list = [];

        if (!$code) {
            return $list;
        }

        $class = new \ReflectionClass($className);
        $property = $class->getProperty($propertyName);
        $orm = $this->annotationReader->getPropertyAnnotation($property, 'Doctrine\\ORM\\Mapping\\Column');

        $locale = null;
        if ($subject && method_exists($subject, 'getCurrentLocale')) {
            $locale = $subject->getCurrentLocale();
        }

        $results = $this->getChoices($code, $locale);

        if (!$flip
            && is_string($orm->type) && $orm->type == "string"
            && $orm->nullable) {
            $list[null] = null;
        }

        foreach ($results as $item) {
            /**
             * @var $item BaseItem
             */
            $list[$item->getName()] = $item->getValue();
        }

        if ($flip) {
            return array_flip($list);
        }

        return $list;
    }

    public function getChoices($code, $locale = null)
    {
        $query = $this->getRepository()->createQueryBuilder('i')
            ->where('i.enabled = true')
            ->where('i.list = :code')->setParameter('code', $code)
            ->getQuery()
        ;

        return $query
            ->getResult();
    }
}
