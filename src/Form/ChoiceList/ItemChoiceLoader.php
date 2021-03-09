<?php

declare(strict_types=1);


namespace Aschaeffer\SonataEditableListBundle\Form\ChoiceList;

use Symfony\Component\Form\ChoiceList\ArrayChoiceList;
use Symfony\Component\Form\ChoiceList\Loader\ChoiceLoaderInterface;

final class ItemChoiceLoader implements ChoiceLoaderInterface
{
    /**
     * The loaded choice list.
     *
     * @var ArrayChoiceList
     */
    private $choiceList;

    /**
     * @var array
     */
    private $choices;

    /**
     * @param array $choices
     */
    public function __construct($choices)
    {
        $this->choices = $choices;
    }

    public function loadChoiceList($value = null)
    {
        if (null !== $this->choiceList) {
            return $this->choiceList;
        }

        return $this->choiceList = new ArrayChoiceList($this->choices, $value);
    }

    public function loadChoicesForValues(array $values, $value = null)
    {
        if (empty($values)) {
            return [];
        }

        return $this->loadChoiceList($value)->getChoicesForValues($values);
    }

    public function loadValuesForChoices(array $choices, $value = null)
    {
        if (empty($choices)) {
            return [];
        }

        return $this->loadChoiceList($value)->getValuesForChoices($choices);
    }
}
