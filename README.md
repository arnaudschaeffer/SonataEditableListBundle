# Sonata Editable List Bundle

Sonata Editalbe List Bundle allow you to define editable list for entities.  


## Installation

Install the package with:

```console
composer require aschaeffer/sonata-editable-list-bundle
```

If you're *not* using Symfony Flex, you'll also need to enable the `Aschaeffer\SonataEditableListBundle\AschaefferSonataEditableListBundle` in your `AppKernel.php` file.

You can then add `SonataEditableList` and `SonataEditableItem` in `App\Entity`. The class can be found in the recipe branch of this repository.

Also, add the following configuration:

```yaml
#config/packages/aschaeffer_sonata_editable_list.yaml
aschaeffer_sonata_editable_list:
  class:
    list: App\Entity\SonataEditableList
    item: App\Entity\SonataEditableItem
```

## Usage

In your entity, add `Listable` annotation to use editable list :

```php
<?php

class User {
    /**
     * @Listable(code="user_gender")
     * @ORM\ManyToOne(
     *     targetEntity="SonataEditableItem", cascade={"persist"}
     * )
     * @ORM\JoinColumn(name="gender_id", referencedColumnName="id")
     *
     * @var SonataEditableItem
     */
    protected $gender;
       
    /**
     * @var SonataEditableItem[] $interests
     * @ORM\ManyToMany(targetEntity="SonataEditableItem")
     * @ORM\JoinTable(name="users_interests",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="item_id", referencedColumnName="id")}
     *      )
     * @Listable(code="user_interests")
     */
    protected $interests;
}
```

Create `user_gender` and `user_interests` list in Sonata admin or use the `sonata:editable_list:create` command to initialze lists and define all the possible values.

In your Sonata admin class, listable property will property in `ShowMapper` and `ListMapper`. When using these properties in `FormMapper` and `DatagridMapper`, you will need to add the following code :

```php
    use Aschaeffer\SonataEditableListBundle\Form\Type\ItemSelectorType;
    class UserAdmin {
        protected function configureFormFieldsProperties(FormMapper $formMapper) {
            $formMapper
                ->add('gender', ItemSelectorType::class,
                    [
                        'model_manager' => $this->getModelManager(),
                        'class' => SonataEditableItem::class,
                        'required' => true,
                        'expanded' => true,
                        'multiple' => false,
                    ]
                )
                ->add('interests', ItemSelectorType::class,
                    [
                        'model_manager' => $this->getModelManager(),
                        'class' => SonataEditableItem::class,
                        'required' => true,
                        'expanded' => true,
                        'multiple' => true,
                    ]
                )
        }
        
        protected function configureDatagridFilters(DatagridMapper $datagridMapper)
        {
            $datagrid
                ->add('gender', null, [],
                    ItemSelectorType::class,
                    [
                        'model_manager' => $this->getModelManager(),
                        'class' => SonataEditableItem::class,
                        'multiple' => true,
                        'field_name' => 'gender',
                        'class_name' => User::class,
                    ]
                )
                ->add('gender', null, [],
                    ItemSelectorType::class,
                    [
                        'model_manager' => $this->getModelManager(),
                        'class' => SonataEditableItem::class,
                        'multiple' => true,
                        'field_name' => 'interests',
                        'class_name' => User::class,
                    ]
                )
        }
    }
    
    
```