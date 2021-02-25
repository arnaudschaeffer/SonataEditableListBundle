<?php
namespace Aschaeffer\EditableList\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslatable;
use Sonata\TranslationBundle\Model\Gedmo\TranslatableInterface;
use Sonata\TranslationBundle\Traits\Gedmo\TranslatableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="editablelist_list")
 * @Gedmo\TranslationEntity(class="App\Entity\Translation\EditableListTranslation")
 * @ORM\HasLifecycleCallbacks()
 */
class EditableList extends AbstractPersonalTranslatable implements TranslatableInterface
{
    use TimestampableEntity;

    use TranslatableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    protected $code;

    /**
     * @var bool $enabled
     *
     * @ORM\Column(type="boolean", options={"default":false})
     */
    protected $enabled = false;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="Item", mappedBy="list", cascade={"persist", "remove"}, fetch="EXTRA_LAZY")
     * @Orm\OrderBy({"position" = "ASC"})
     */
    protected $items;

    /**
     * @ORM\OneToMany(
     *   targetEntity="App\Entity\Translation\EditableListTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    protected $translations;

    public function __construct()
    {
        parent::__construct();
        $this->items = new ArrayCollection();
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
     * @return Collection|Item[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setList($this);
        }

        return $this;
    }

    public function removeItem(Item $item): self
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
     * @param $locale
     */
    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

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