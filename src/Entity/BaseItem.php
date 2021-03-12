<?php
namespace Aschaeffer\SonataEditableListBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslatable;
use Sonata\TranslationBundle\Model\Gedmo\TranslatableInterface;
use Sonata\TranslationBundle\Traits\Gedmo\TranslatableTrait;

abstract class BaseItem extends AbstractPersonalTranslatable implements TranslatableInterface
{
    use TimestampableEntity;

    use TranslatableTrait;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var int
     */
    protected $position = 1;

    /**
     * @var bool $enabled
     */
    protected $enabled = true;

    /**
     * @var BaseList
     */
    protected $list;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
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

    /**
     * @param string $value
     */
    public function setValue(?string $value): void
    {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @param int $position
     * @return BaseItem
     */
    public function setPosition(int $position): BaseItem
    {
        $this->position = $position;
        return $this;
    }

    /**
     * @return BaseList
     */
    public function getList(): ?BaseList
    {
        return $this->list;
    }

    /**
     * @param BaseList $list
     */
    public function setList(?BaseList $list): void
    {
        $this->list = $list;
    }

    public function __toString()
    {
        return $this->getName();
    }
}