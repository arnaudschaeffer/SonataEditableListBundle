<?php

namespace Aschaeffer\SonataEditableListBundle\Admin;

use Aschaeffer\SonataEditableListBundle\Entity\ListManager;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManagerInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\Form\Type\CollectionType;
use Sonata\Form\Validator\ErrorElement;


class ListAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'editablelist/list';
    protected $baseRoutePattern = 'editablelist/list';

    protected $datagridValues = [
        '_page' => 1,
        '_sort_order' => 'DESC',
        '_sort_by' => 'updated_at',
    ];

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var ListManager
     */
    protected $listManager;
    
    /**
     * @var Reader
     */
    protected $annotationReader;

    public function __construct(string $code,
                                string $class,
                                string $baseControllerName)
    {
        parent::__construct($code, $class, $baseControllerName);
    }

    public function setListManager(ListManager $listManager): void
    {
        $this->listManager = $listManager;
    }

    /**
     * @return ListManager
     */
    public function getListManager()
    {
        return $this->listManager;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $description = "";
        if (!$this->isCurrentRoute('create')) {
            $usages = $this->listManager->getEntitiesUsage($this->subject->getCode());

            if (!empty($usages)) {
                $description = $this->getTranslator()->trans("form.header_help", [], $this->translationDomain) . "<br/><ul>";
                foreach ($usages as $entity => $properties) {
                    $description .= "<li>" . $entity. " : " . implode(', ', $properties) . "</li>";
                }
                $description .= "</ul>";
            }
        }

        $isNew = $this->isCurrentRoute('create');
        $formMapper
            ->with('form.with_properties', [
                'description' => $description,
                'class'       => 'col-xs-12 col-lg-6',
            ])
                ->add('code', null, ['attr' => ['readonly' => !$isNew,],])
                ->add('name')
                ->add('enabled')
            ->end()
            ->ifTrue(!$this->isCurrentRoute('create'))
                ->with('form.with_items')
                ->add('items', CollectionType::class, ['by_reference' => false], [
                        'edit' => 'inline',
                        'inline' => 'table',
                        'sortable' => 'position',
                        'link_parameters' => ['from_list' => true, 'locale' => $this->subject->getLocale(),],
                    ])
                ->end()
            ->ifEnd()
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('code')
            ->add('name')
            ->add('enabled', null, ['editable' => true])
            ->add('itemCount', null, ['template' => '@AschaefferSonataEditableList/ListAdmin/badge_count.html.twig',])
            ->add('usage', null, ['template' => '@AschaefferSonataEditableList/ListAdmin/usage_count.html.twig', 'data' => $this->listManager->getEntitiesUsage()])
        ;
    }

    public function validate(ErrorElement $errorElement, $object)
    {
        foreach ($object->getItems() as $item) {
            $item->setLocale($object->getLocale());
        }
    }
}