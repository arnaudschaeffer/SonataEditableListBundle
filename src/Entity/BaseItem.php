<?php
namespace Aschaeffer\SonataEditableListBundle\Entity;

use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;

abstract class BaseItem implements TranslatableInterface, TimestampableInterface
{
    use TimestampableTrait;
    use TranslatableTrait;

    protected ?int $id;
    protected ?string $name;
    protected ?string $value;
    protected int $position = 1;
    protected bool $enabled = true;
    protected ?BaseList $list;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): BaseItem
    {
        $this->position = $position;
        return $this;
    }

    public function getList(): ?BaseList
    {
        return $this->list;
    }

    public function setList(?BaseList $list): void
    {
        $this->list = $list;
    }

    public function __toString()
    {
        return $this->getName();
    }
}