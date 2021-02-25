<?php
namespace Aschaeffer\EditableList\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslatable;
use Sonata\TranslationBundle\Model\Gedmo\TranslatableInterface;
use Sonata\TranslationBundle\Traits\Gedmo\TranslatableTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ItemRepository")
 * @ORM\Table(name="editablelist_item",indexes={@ORM\Index(name="editablelist_item_idx", columns={"value"})})
 * @Gedmo\TranslationEntity(class="App\Entity\Translation\ItemTranslation")
 * @ORM\HasLifecycleCallbacks()
 */
class Item extends AbstractPersonalTranslatable implements TranslatableInterface
{
    use TimestampableEntity;

    use TranslatableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(type="string", nullable=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $value;

    /**
     * @var int
     * @ORM\Column(name="position", type="integer")
     */
    protected $position = 1;

    /**
     * @var bool $enabled
     *
     * @ORM\Column(type="boolean", options={"default":true})
     */
    protected $enabled = true;

    /**
     * @var EditableList
     * @ORM\ManyToOne(targetEntity="EditableList", inversedBy="items")
     * @ORM\JoinColumn(name="list_code", referencedColumnName="code", nullable=true)
     */
    protected $list;

    /**
     * @ORM\OneToMany(
     *   targetEntity="App\Entity\Translation\ItemTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    protected $translations;

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
     * @return Item
     */
    public function setPosition(int $position): Item
    {
        $this->position = $position;
        return $this;
    }

    /**
     * @return EditableList
     */
    public function getList(): ?EditableList
    {
        return $this->list;
    }

    /**
     * @param EditableList $list
     */
    public function setList(?EditableList $list): void
    {
        $this->list = $list;
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
}