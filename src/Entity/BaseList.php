<?php
namespace Aschaeffer\SonataEditableListBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslatable;
use Sonata\TranslationBundle\Model\Gedmo\TranslatableInterface;
use Sonata\TranslationBundle\Traits\Gedmo\TranslatableTrait;

abstract class BaseList extends AbstractPersonalTranslatable implements TranslatableInterface
{
    use TimestampableEntity;

    use TranslatableTrait;

    protected $code;

    protected $enabled = false;

    protected $name;

    protected $items;

    protected $translations;

    public function __construct()
    {
        parent::__construct();
        $this->items = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->code;
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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return Collection|BaseItem[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(BaseItem $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setList($this);
        }

        return $this;
    }

    public function removeItem(BaseItem $item): self
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
            // set the owning side to null (unless already changed)
            if ($item->getList() === $this) {
                $item->setList(null);
            }
        }

        return $this;
    }

    /** ------------------------------ **/
    /** Helpers                        **/
    /** ------------------------------ **/

    /**
     * @return int
     */
    public function getItemCount()
    {
        return $this->getItems()->count();
    }

    public function __toString()
    {
        if ($this->code) {
            return $this->code;
        }

        return "Liste éditable";
    }
}