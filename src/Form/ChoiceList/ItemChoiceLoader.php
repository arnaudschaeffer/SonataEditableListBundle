<?php

declare(strict_types=1);


namespace Aschaeffer\SonataEditableListBundle\Form\ChoiceList;

use Symfony\Component\Form\ChoiceList\ArrayChoiceList;
use Symfony\Component\Form\ChoiceList\ChoiceListInterface;
use Symfony\Component\Form\ChoiceList\Loader\ChoiceLoaderInterface;

final class ItemChoiceLoader implements ChoiceLoaderInterface
{
    private ?ArrayChoiceList $choiceList = null;
    private array $choices = [];

    /**
     * @param array $choices
     */
    public function __construct(array $choices)
    {
        $this->choices = $choices;
    }

    public function loadChoiceList(?callable $value = null): ChoiceListInterface
    {
        if (null !== $this->choiceList) {
            return $this->choiceList;
        }

        return $this->choiceList = new ArrayChoiceList($this->choices, $value);
    }

    public function loadChoicesForValues(array $values, $value = null): array
    {
        if (empty($values)) {
            return [];
        }

        return $this->loadChoiceList($value)->getChoicesForValues($values);
    }

    public function loadValuesForChoices(array $choices, $value = null): array
    {
        if (empty($choices)) {
            return [];
        }

        return $this->loadChoiceList($value)->getValuesForChoices($choices);
    }
}
