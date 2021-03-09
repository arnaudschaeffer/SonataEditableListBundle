<?php

namespace Aschaeffer\SonataEditableListBundle\Entity\Translation;

use Doctrine\ORM\Mapping as ORM;
use Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslation;

/**
 * @ORM\Entity
 * @ORM\Table(name="sonata_item__translations",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_idx_item", columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 */
class SonataEditableItemTranslation extends AbstractPersonalTranslation {

    /**
     * Convenient constructor
     *
     * @param string $locale
     * @param string $field
     * @param string $value
     */
    public function __construct($locale, $field, $value)
    {
        parent::__construct($locale, $field, $value);
    }

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SonataEditableItem", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;
}