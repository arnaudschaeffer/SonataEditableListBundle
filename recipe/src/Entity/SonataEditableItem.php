<?php
namespace App\Entity;

use App\Entity\Translation\SonataEditableItemTranslation;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Aschaeffer\SonataEditableListBundle\Entity\BaseItem;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 * @ORM\Table(name="editablelist__item",indexes={@ORM\Index(name="editablelist__item_idx", columns={"value"})})
 * @Gedmo\TranslationEntity(class="App\Entity\Translation\SonataEditableItemTranslation")
 * @ORM\HasLifecycleCallbacks()
 */
class SonataEditableItem extends BaseItem
{
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
     * @Groups({"item_get"})
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"item_get"})
     */
    protected $value;

    /**
     * @var int
     * @ORM\Column(name="position", type="integer")
     * @Groups({"item_get"})
     */
    protected $position = 1;

    /**
     * @var bool $enabled
     *
     * @ORM\Column(type="boolean", options={"default":true})
     * @Groups({"item_get"})
     */
    protected $enabled = true;

    /**
     * @var SonataEditableList
     * @ORM\ManyToOne(targetEntity="SonataEditableList", inversedBy="items")
     * @ORM\JoinColumn(name="list_code", referencedColumnName="code", nullable=true)
     */
    protected $list;

    /**
     * @var App\Entity\Translation\SonataEditableItemTranslation[]
     * @ORM\OneToMany(
     *   targetEntity="App\Entity\Translation\SonataEditableItemTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    protected $translations;

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