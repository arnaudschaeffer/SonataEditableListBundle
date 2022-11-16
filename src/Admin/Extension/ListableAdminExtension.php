<?php

declare(strict_types=1);

namespace Aschaeffer\SonataEditableListBundle\Admin\Extension;

use Aschaeffer\SonataEditableListBundle\Annotation\Listable;
use Aschaeffer\SonataEditableListBundle\Entity\ItemManager;
use Doctrine\Common\Annotations\AnnotationReader;
use Gedmo\Translatable\TranslatableListener;
use Sonata\AdminBundle\Admin\AbstractAdminExtension;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\Form\Validator\ErrorElement;
use Symfony\Component\VarDumper\VarDumper;


class ListableAdminExtension extends AbstractAdminExtension
{
    /**
     * @var AnnotationReader
     */
    protected AnnotationReader $annotationReader;

    /**
     * @var ItemManager
     */
    protected $itemManager;

    public function __construct(AnnotationReader $annotationReader, ItemManager $itemManager)
    {
        $this->annotationReader = $annotationReader;
        $this->itemManager = $itemManager;
    }

//    public function validate(AdminInterface $admin, ErrorElement $errorElement, $object)
//    {
//        $modelClassReflection = new \ReflectionClass(get_class($object));
//
//        foreach ($modelClassReflection->getProperties() as $reflectionProperty) {
//            if ($this->annotationReader->getPropertyAnnotation($reflectionProperty, Listable::ANNOTATION_NAME)) {
//                if ($this->annotationReader->getPropertyAnnotation($reflectionProperty, 'Doctrine\\ORM\\Mapping\\ManyToOne')) {
//                    $getter = 'get' . $reflectionProperty->getName();
//                    $setter = 'set' . $reflectionProperty->getName();
//
//                    $item = $this->itemManager->find($object->$getter());
//                    $object->$setter($item);
//                }
//
//                if ($this->annotationReader->getPropertyAnnotation($reflectionProperty, 'Doctrine\\ORM\\Mapping\\ManyToMany')) {
//                    $getter = 'get' . $reflectionProperty->getName();
//                    $setter = 'set' . $reflectionProperty->getName();
//                    $items = $this->itemManager->findBy(['id' => $object->$getter(),]);
//                    $object->$setter($items);
//                }
//            }
//        }
    //}
}
