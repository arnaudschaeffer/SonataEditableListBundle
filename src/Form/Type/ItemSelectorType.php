<?php

declare(strict_types=1);

namespace Aschaeffer\SonataEditableListBundle\Form\Type;

use Aschaeffer\SonataEditableListBundle\Entity\ItemManager;
use Aschaeffer\SonataEditableListBundle\Form\ChoiceList\ItemChoiceLoader;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\Doctrine\Model\ManagerInterface;
use Sonata\DoctrineORMAdminBundle\Admin\FieldDescription;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\ChoiceLoaderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;


class ItemSelectorType extends AbstractType
{
    /**
     * @var ItemManager
     */
    protected $manager;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var string
     */
    protected $code;

    public function __construct(ManagerInterface $manager,
                                RouterInterface $router,
                                TranslatorInterface $translator)
    {
        $this->manager = $manager;
        $this->router = $router;
        $this->translator = $translator;
    }

    /**
     * NEXT_MAJOR: Remove this method.
     *
     * @deprecated since sonata-project/classification-bundle 3.10, to be removed in version 4.0.
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choice_loader' => function (Options $opts): ChoiceLoaderInterface {
                return new ItemChoiceLoader(array_flip($this->getChoices($opts)));
            },
            'help' => function(Options $opts): string{
                $code = $this->getCode($opts);
                if (!$code) {
                    return "";
                }

                return '<a href="' . $this->router->generate('editablelist/list_edit', ['id' => $code,]) . '" target="_blank"><i class="fa fa-pencil" aria-hidden="true"></i> ' . $this->translator->trans('list.edit_link', [], 'SonataEditableListBundle') . '</a>';
            },
            'help_html' => true,
            'btn_add' => false,
            'field_name' => null,
            'class_name' => null,
            'listable_code' => null,
        ]);
    }

    protected function getCode(Options $options)
    {
        if (isset($options['listable_code'])) {
            return $options['listable_code'];
        }
        $className = $this->getClass($options);
        $fieldName = $this->getFieldName($options);
        $code = $this->manager->getListableCode($className, $fieldName);

        if ($code === null) {
            throw new \Exception(sprintf("Is there a @Listable annotation on property %s for entity %s ?", $className, $fieldName));
        }

        return $code;
    }

    /**
     * @param Options $options
     * @return array
     * @throws \Exception
     */
    public function getChoices(Options $options)
    {
        $code = $this->getCode($options);
        $items = $this->manager->getChoices($code);

        $choices = [];

        foreach ($items as $item) {
            $choices[$item->getId()] = $item->getName();
        }

        return $choices;
    }

    protected function getClass($options)
    {
        if (isset($options['sonata_field_description']) && $options['sonata_field_description'] !== null) {

            /**
             * @var $fieldDescription FieldDescription
             */
            $fieldDescription = $options['sonata_field_description'];

            return $fieldDescription->getAdmin()->getClass();
        }

        if (isset($options['class_name'])) {
            return $options['class_name'];
        }

        throw new \Exception("Unable to retrieve class_name");
    }

    protected function getFieldName($options)
    {
        if (isset($options['sonata_field_description']) && $options['sonata_field_description'] !== null) {
            /**
             * @var $fieldDescription FieldDescription
             */
            $fieldDescription = $options['sonata_field_description'];

            return $fieldDescription->getFieldName();
        }

        if (isset($options['field_name'])) {
            return $options['field_name'];
        }

        throw new \Exception("Unable to retrieve field name");
    }

    public function getParent()
    {
        return ModelType::class;
    }

    public function getBlockPrefix()
    {
        return 'aschaeffer_sonata_item_selector';
    }
}
