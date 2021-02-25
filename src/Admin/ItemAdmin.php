<?php

namespace Aschaeffer\SonataEditableListBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;


class ItemAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'editablelist/item';
    protected $baseRoutePattern = 'editablelist/item';

    protected $datagridValues = [
        '_page' => 1,
        '_sort_order' => 'DESC',
        '_sort_by' => 'updated_at',
    ];

    protected function configureFormFields(FormMapper $formMapper)
    {
        $fromList = false;
        $locale = null;
        if ($this->hasParentFieldDescription()) {
            $linkParameters = $this->getParentFieldDescription()->getOption('link_parameters', []);
            $fromList = isset($linkParameters['from_list']);
            $locale = isset($linkParameters['locale']);

            if ($this->subject) {
                $this->subject->setLocale($locale);
                $this->subject->setTranslatableLocale($locale);
            }
        }

        $formMapper
            ->tab('admin.form.common.properties')
                ->with('admin.form.common.properties', [
                    'class'       => 'col-xs-12 col-lg-4',
                ])
                    ->add('name')
                    ->add('value')
                    ->add('enabled')
                    ->add('position', HiddenType::class)
                ->end()
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