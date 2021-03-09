<?php
namespace App\Entity;

use Aschaeffer\SonataEditableListBundle\Entity\BaseList;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sonata\TranslationBundle\Traits\Gedmo\TranslatableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="editablelist__list")
 * @Gedmo\TranslationEntity(class="App\Entity\Translation\SonataEditableListTranslation")
 * @ORM\HasLifecycleCallbacks()
 */
class SonataEditableList extends BaseList
{
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
     * @ORM\OneToMany(targetEntity="SonataEditableItem", mappedBy="list", cascade={"persist", "remove"}, fetch="EXTRA_LAZY")
     * @Orm\OrderBy({"position" = "ASC"})
     */
    protected $items;

    /**
     * @var App\Entity\Translation\SonataEditableListTranslation[]
     * @ORM\OneToMany(
     *   targetEntity="App\Entity\Translation\SonataEditableListTranslation",
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

        return "Editable List";
    }
}