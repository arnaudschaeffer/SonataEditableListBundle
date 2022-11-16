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

    protected array $datagridValues = [
        '_page' => 1,
        '_sort_order' => 'DESC',
        '_sort_by' => 'updated_at',
    ];

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
        ->add('list', TextType::class, ['attr' => ['readonly' => true,],])

        ->add('name', null, ['required' => true,])
        ->add('value', null, ['required' => true,])
        ->add('enabled')
            ;
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $showList = false;
        if (!$this->hasParentFieldDescription() && $this->getSubject() && $this->getSubject()->getList()) {
            $showList = true;
        }

        $form
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

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('value', null, ['route' => ['name' => 'edit']])
            ->add('name')
            ->add('list.code')
        ;
    }
}