<?php

namespace Aschaeffer\SonataEditableListBundle\Entity;

use Aschaeffer\SonataEditableListBundle\Annotation\Listable;
use Aschaeffer\SonataEditableListBundle\Model\ItemManagerInterface;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Sonata\DatagridBundle\Pager\Doctrine\Pager;
use Sonata\DatagridBundle\ProxyQuery\Doctrine\ProxyQuery;
use Sonata\Doctrine\Entity\BaseEntityManager;

class ItemManager extends BaseEntityManager implements ItemManagerInterface
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

        $pager = new Pager();
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
    public function getListChoices($propertyName, $subject, $className, $flip = false)
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
        if ($subject && method_exists($subject, 'getLocale')) {
            $locale = $subject->getLocale();
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

//    public function getLabels($values, $code, $implode = ', ')
//    {
//        $labels = $this->getRepository()->createQueryBuilder('i')
//            ->select('name')
//            ->innerJoin('i.list', 'l')
//            ->where('l.code = :code')->setParameter('code', $code)
//            ->where('i.value IN (:values)')->setParameter('values', $values)
//            ->getQuery()->getScalarResult();
//
//        return implode($implode, $labels);
//    }

//    public function getLocales($locale = null)
//    {
//        return $this->getRepository()->createQueryBuilder('i')
//            ->select('l.code, i.name, i.value')
//            ->innerJoin('i.list', 'l')
//            ->where('l.enabled = true')
//            ->where('i.enabled = true')
//            ->getQuery()
//            ->setHint(Query::HINT_CUSTOM_OUTPUT_WALKER, 'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker')
//            ->setHint(\Gedmo\Translatable\TranslatableListener::HINT_TRANSLATABLE_LOCALE, $locale)
//            ->setHint(Query::HINT_REFRESH, true)
//            ->getScalarResult();
//    }

    public function getChoices($code, $locale = null)
    {
        $query = $this->getRepository()->createQueryBuilder('i')
            ->where('i.enabled = true')
            ->where('i.list = :code')->setParameter('code', $code)
            ->getQuery()
        ;

        if ($locale != null) {
            $query
                ->setHint(Query::HINT_CUSTOM_OUTPUT_WALKER, 'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker')
                ->setHint(\Gedmo\Translatable\TranslatableListener::HINT_TRANSLATABLE_LOCALE, $locale)
                ->setHint(Query::HINT_REFRESH, true);
        }

        return $query
            ->getResult();
    }

//    /**
//     * @param $id
//     * @return null|object
//     */
//    public function getById($id)
//    {
//        return $this->getRepository()->find($id);
//    }
//
//    /**
//     * @param $ids
//     * @return array
//     */
//    public function getByIds($ids)
//    {
//        return $this->getRepository()->findBy(['id' => $ids]);
//    }
}
