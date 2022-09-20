<?php

namespace Aschaeffer\SonataEditableListBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class ItemAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'editablelist/item';
    protected $baseRoutePattern = 'editablelist/item';

    protected $datagridValues = [
        '_page' => 1,
        '_sort_order' => 'DESC',
        '_sort_by' => 'updated_at',
    ];

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
        ->add('list', TextType::class, ['attr' => ['readonly' => true,],])

        ->add('name', null, ['required' => true,])
        ->add('value', null, ['required' => true,])
        ->add('enabled')
            ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $showList = false;
        if (!$this->hasParentFieldDescription() && $this->subject && $this->subject->getList()) {
            $showList = true;
        }

        $formMapper
            ->with('form.with_properties', [
                'class'       => 'col-xs-12 col-lg-4',
            ])
                ->ifTrue($showList)
                    ->add('list', TextType::class, ['attr' => ['readonly' => true,],])
                ->ifEnd()
                ->add('name', null, ['required' => true,])
                ->add('value', null, ['required' => true,])
                ->add('enabled')
                ->add('position', HiddenType::class)
            ->end()
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {

        $listMapper
            ->addIdentifier('value', null, ['route' => ['name' => 'edit']])
            ->add('name')
            ->add('list.code')
        ;
    }
}